<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\FacturaPaciente;
use App\Models\MetodoPago;
use App\Models\TasaDolar;
use App\Models\Administrador;
use App\Notifications\CitaActualizada;
use App\Notifications\PagoConfirmado;
use App\Notifications\PagoRechazado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
                // Métodos restringidos para administradores locales
                $restrictedMethods = ['edit', 'update', 'destroy'];
                $method = $request->route()->getActionMethod();

                if (in_array($method, $restrictedMethods)) {
                    abort(403, 'Solo los administradores Root tienen permiso para editar o eliminar registros de pago.');
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Pago::with([
            'facturaPaciente.cita.paciente',
            'facturaPaciente.cita.medico',
            'metodoPago',
            'tasaAplicada',
            'confirmadoPor'
        ])
        ->where('status', true);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('referencia', 'like', "%$buscar%")
                  ->orWhereHas('facturaPaciente.cita.paciente', function($qP) use ($buscar) {
                      $qP->where('primer_nombre', 'like', "%$buscar%")
                         ->orWhere('primer_apellido', 'like', "%$buscar%")
                         ->orWhere('numero_documento', 'like', "%$buscar%");
                  });
            });
        }

        $pagos = $query->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->withQueryString();

        // Calcular Estadisticas Globales
        $totalConfirmados = Pago::where('estado', 'Confirmado')
                                ->where('status', true)
                                ->sum('monto_equivalente_usd');
        
        $totalPendientes = Pago::where('estado', 'Pendiente')
                               ->where('status', true)
                               ->count();
        
        $totalHoy = Pago::where('estado', 'Confirmado')
                        ->where('status', true)
                        ->whereDate('fecha_pago', now())
                        ->sum('monto_equivalente_usd');
        
        return view('shared.pagos.index', compact('pagos', 'totalConfirmados', 'totalPendientes', 'totalHoy'));
    }

    public function create()
    {
        $user = auth()->user();
        
        $query = FacturaPaciente::with(['paciente', 'cita.consultorio'])
                                ->whereIn('status_factura', ['Emitida', 'Parcialmente Pagada'])
                                ->where('status', true);

        // Aplicar filtro si es administrador local
        if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios->pluck('id')->toArray();
            $query->whereHas('cita', function ($q) use ($consultorioIds) {
                $q->whereIn('consultorio_id', $consultorioIds);
            });
        }

        $facturas = $query->orderBy('created_at', 'desc')->get();
        
        $metodosPago = MetodoPago::where('status', true)->get();
        $tasas = TasaDolar::where('status', true)->orderBy('fecha_tasa', 'desc')->get();
        
        return view('shared.pagos.create', compact('facturas', 'metodosPago', 'tasas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_factura_paciente' => 'required|exists:facturas_pacientes,id',
            'id_metodo' => 'required|exists:metodo_pago,id_metodo',
            'fecha_pago' => 'required|date',
            'monto_pagado_bs' => 'required|numeric|min:0',
            'tasa_aplicada_id' => 'required|exists:tasas_dolar,id',
            'referencia' => 'required|max:255',
            'comentarios' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $factura = FacturaPaciente::findOrFail($request->id_factura_paciente);
        $tasa = TasaDolar::findOrFail($request->tasa_aplicada_id);

        // Calcular equivalente en USD
        $montoEquivalenteUSD = $request->monto_pagado_bs / $tasa->valor;

        // Verificar que el pago no exceda el monto de la factura
        $totalPagado = Pago::where('id_factura_paciente', $factura->id)
                          ->where('status', true)
                          ->where('estado', 'Confirmado')
                          ->sum('monto_equivalente_usd');

        if (($totalPagado + $montoEquivalenteUSD) > $factura->monto_usd) {
            return redirect()->back()->with('error', 'El pago excede el monto total de la factura')->withInput();
        }

        $pago = Pago::create([
            'id_factura_paciente' => $factura->id,
            'id_metodo' => $request->id_metodo,
            'fecha_pago' => $request->fecha_pago,
            'monto_pagado_bs' => $request->monto_pagado_bs,
            'monto_equivalente_usd' => $montoEquivalenteUSD,
            'tasa_aplicada_id' => $tasa->id,
            'referencia' => $request->referencia,
            'comentarios' => $request->comentarios,
            'estado' => $this->getEstadoInicial($request->id_metodo),
            'status' => true
        ]);

        // Actualizar estado de la factura
        $this->actualizarEstadoFactura($factura->id);

        // Enviar notificación si el pago fue confirmado automáticamente
        if ($pago->estado == 'Confirmado') {
            $this->enviarNotificacionPago($pago);
        }

        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente');
    }

    public function show($id)
    {
        $pago = Pago::with([
            'facturaPaciente.cita.paciente.usuario',
            'facturaPaciente.cita.medico',
            'metodoPago',
            'tasaAplicada',
            'confirmadoPor'
        ])->findOrFail($id);

        return view('shared.pagos.show', compact('pago'));
    }

    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $facturas = FacturaPaciente::where('status', true)->get();
        $metodosPago = MetodoPago::where('status', true)->get();
        $tasas = TasaDolar::where('status', true)->orderBy('fecha_tasa', 'desc')->get();
        $administradores = Administrador::where('status', true)->get();

        return view('shared.pagos.edit', compact('pago', 'facturas', 'metodosPago', 'tasas', 'administradores'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_factura_paciente' => 'required|exists:facturas_pacientes,id',
            'id_metodo' => 'required|exists:metodo_pago,id_metodo',
            'fecha_pago' => 'required|date',
            'monto_pagado_bs' => 'required|numeric|min:0',
            'tasa_aplicada_id' => 'required|exists:tasas_dolar,id',
            'referencia' => 'required|max:255',
            'comentarios' => 'nullable|string',
            'estado' => 'required|in:Pendiente,Confirmado,Rechazado,Reembolsado',
            'confirmado_por' => 'nullable|exists:administradores,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pago = Pago::findOrFail($id);
        $factura = FacturaPaciente::findOrFail($request->id_factura_paciente);
        $tasa = TasaDolar::findOrFail($request->tasa_aplicada_id);

        // Recalcular equivalente en USD
        $montoEquivalenteUSD = $request->monto_pagado_bs / $tasa->valor;

        $pago->update([
            'id_factura_paciente' => $factura->id,
            'id_metodo' => $request->id_metodo,
            'fecha_pago' => $request->fecha_pago,
            'monto_pagado_bs' => $request->monto_pagado_bs,
            'monto_equivalente_usd' => $montoEquivalenteUSD,
            'tasa_aplicada_id' => $tasa->id,
            'referencia' => $request->referencia,
            'comentarios' => $request->comentarios,
            'estado' => $request->estado,
            'confirmado_por' => $request->confirmado_por
        ]);

        // Recalcular estado de la factura
        $this->actualizarEstadoFactura($factura->id);

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado exitosamente');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $facturaId = $pago->id_factura_paciente;
        
        $pago->update(['status' => false]);

        // Recalcular estado de la factura
        $this->actualizarEstadoFactura($facturaId);

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado exitosamente');
    }

    public function confirmarPago(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            $pago = Pago::with(['facturaPaciente.cita'])->findOrFail($id);
            
            $adminId = auth()->user()->administrador->id ?? null;
            if (!$adminId) {
                throw new \Exception('No se pudo identificar el perfil de administrador para confirmar el pago.');
            }

            $pago->update([
                'estado' => 'Confirmado',
                'confirmado_por' => $adminId
            ]);

            // Actualizar estado de la factura
            $this->actualizarEstadoFactura($pago->id_factura_paciente);

            // Actualizar estado de la cita a "Confirmada"
            if ($pago->facturaPaciente && $pago->facturaPaciente->cita) {
                $cita = $pago->facturaPaciente->cita;
                
                // Solo cambiar a Confirmada si está en Programada
                if ($cita->estado_cita == 'Programada') {
                    $cita->update(['estado_cita' => 'Confirmada']);
                }

                // Ejecutar lógica de facturación avanzada (reparto de porcentajes)
                if (!$cita->facturaCabecera) {
                    $this->ejecutarFacturacionAvanzada($cita);
                }
            }

            \DB::commit();

            // Enviar notificación
            $this->enviarNotificacionPago($pago);
            
            // Enviar notificación al paciente o representante (si es paciente especial)
            if ($cita && $cita->paciente) {
                $paciente = $cita->paciente;
                $pacienteEspecial = $paciente->pacienteEspecial;
                
                if ($pacienteEspecial && $pacienteEspecial->representante) {
                    // Es paciente especial: notificar al representante
                    $representante = $pacienteEspecial->representante;
                    $pacienteRepresentante = \App\Models\Paciente::where('tipo_documento', $representante->tipo_documento)
                                              ->where('numero_documento', $representante->numero_documento)
                                              ->first();
                    
                    if ($pacienteRepresentante) {
                        $pacienteRepresentante->notify(new \App\Notifications\PagoConfirmado($pago));
                    }
                } else {
                    // Paciente regular: notificar directamente
                    $paciente->notify(new \App\Notifications\PagoConfirmado($pago));
                }

                // Notificar al médico sobre el pago confirmado
                if ($cita->medico) {
                    $cita->medico->notify(new \App\Notifications\Medico\PagoConfirmadoCita($pago));
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Pago confirmado y cita actualizada exitosamente.']);
            }

            return redirect()->back()->with('success', 'Pago confirmado y cita actualizada exitosamente.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al confirmar pago: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al confirmar el pago: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Error al confirmar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar la lógica de facturación avanzada con reparto de porcentajes
     */
    private function ejecutarFacturacionAvanzada($cita)
    {
        $facturaPaciente = $cita->facturaPaciente;
        if (!$facturaPaciente) {
            return;
        }

        $tasa = $facturaPaciente->tasa;
        if (!$tasa) {
            $tasa = TasaDolar::where('status', true)->orderBy('fecha_tasa', 'desc')->first();
        }

        if (!$tasa) {
            \Log::error('No se encontró tasa de cambio para facturación avanzada');
            return;
        }

        // Crear cabecera de factura avanzada
        $facturaCabecera = \App\Models\FacturaCabecera::create([
            'cita_id' => $cita->id,
            'nro_control' => $this->generarNumeroControl(),
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $cita->medico_id,
            'tasa_id' => $tasa->id,
            'fecha_emision' => now(),
            'status' => true
        ]);

        // Obtener configuración de reparto
        $configReparto = \App\Models\ConfiguracionReparto::where('medico_id', $cita->medico_id)
                                            ->where('consultorio_id', $cita->consultorio_id)
                                            ->first();

        if (!$configReparto) {
            // Usar configuración por defecto (sin consultorio específico)
            $configReparto = \App\Models\ConfiguracionReparto::where('medico_id', $cita->medico_id)
                                                ->whereNull('consultorio_id')
                                                ->first();
        }

        if (!$configReparto) {
            // Configuración por defecto si no existe
            $configReparto = new \stdClass();
            $configReparto->porcentaje_medico = 70.00;
            $configReparto->porcentaje_consultorio = 20.00;
            $configReparto->porcentaje_sistema = 10.00;
        }

        // Crear detalles de factura
        $this->crearDetallesFactura($facturaCabecera, $cita, $configReparto);

        // Crear totales de factura
        $this->crearTotalesFactura($facturaCabecera, $tasa);
    }

    private function generarNumeroControl()
    {
        $year = date('Y');
        $sequence = \App\Models\FacturaCabecera::whereYear('fecha_emision', $year)->count() + 1;
        return 'FACT-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    private function crearDetallesFactura($facturaCabecera, $cita, $configReparto)
    {
        $tarifaUSD = $cita->tarifa + $cita->tarifa_extra;

        // Detalle para el médico
        \App\Models\FacturaDetalle::create([
            'cabecera_id' => $facturaCabecera->id,
            'entidad_tipo' => 'Medico',
            'entidad_id' => $cita->medico_id,
            'descripcion' => 'Honorarios médicos (' . $configReparto->porcentaje_medico . '%)',
            'cantidad' => 1,
            'precio_unitario_usd' => $tarifaUSD * ($configReparto->porcentaje_medico / 100),
            'subtotal_usd' => $tarifaUSD * ($configReparto->porcentaje_medico / 100),
            'status' => true
        ]);

        // Detalle para el consultorio (si aplica)
        if ($cita->consultorio_id && $configReparto->porcentaje_consultorio > 0) {
            \App\Models\FacturaDetalle::create([
                'cabecera_id' => $facturaCabecera->id,
                'entidad_tipo' => 'Consultorio',
                'entidad_id' => $cita->consultorio_id,
                'descripcion' => 'Uso de consultorio (' . $configReparto->porcentaje_consultorio . '%)',
                'cantidad' => 1,
                'precio_unitario_usd' => $tarifaUSD * ($configReparto->porcentaje_consultorio / 100),
                'subtotal_usd' => $tarifaUSD * ($configReparto->porcentaje_consultorio / 100),
                'status' => true
            ]);
        }

        // Detalle para el sistema
        if ($configReparto->porcentaje_sistema > 0) {
            \App\Models\FacturaDetalle::create([
                'cabecera_id' => $facturaCabecera->id,
                'entidad_tipo' => 'Sistema',
                'entidad_id' => null,
                'descripcion' => 'Comisión del sistema (' . $configReparto->porcentaje_sistema . '%)',
                'cantidad' => 1,
                'precio_unitario_usd' => $tarifaUSD * ($configReparto->porcentaje_sistema / 100),
                'subtotal_usd' => $tarifaUSD * ($configReparto->porcentaje_sistema / 100),
                'status' => true
            ]);
        }
    }

    private function crearTotalesFactura($facturaCabecera, $tasa)
    {
        $detalles = $facturaCabecera->detalles;

        foreach ($detalles as $detalle) {
            $baseImponibleUSD = $detalle->subtotal_usd;
            $totalFinalUSD = $baseImponibleUSD;
            $totalFinalBS = $totalFinalUSD * $tasa->valor;

            \App\Models\FacturaTotal::create([
                'cabecera_id' => $facturaCabecera->id,
                'entidad_tipo' => $detalle->entidad_tipo,
                'entidad_id' => $detalle->entidad_id,
                'base_imponible_usd' => $baseImponibleUSD,
                'impuestos_usd' => 0,
                'total_final_usd' => $totalFinalUSD,
                'total_final_bs' => $totalFinalBS,
                'estado_liquidacion' => 'Pendiente',
                'status' => true
            ]);
        }
    }

    public function rechazarPago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }
            return redirect()->back()->withErrors($validator);
        }

        $pago = Pago::findOrFail($id);
        
        $pago->update([
            'estado' => 'Rechazado',
            'comentarios' => $request->motivo . ' - ' . ($pago->comentarios ?? '')
        ]);

        // Actualizar estado de la factura
        $this->actualizarEstadoFactura($pago->id_factura_paciente);
        
        
        // Enviar notificación al paciente o representante (si es paciente especial)
        if ($pago->facturaPaciente && $pago->facturaPaciente->cita && $pago->facturaPaciente->cita->paciente) {
            $cita = $pago->facturaPaciente->cita;
            $paciente = $cita->paciente;
            
            // Verificar si es un paciente especial
            $pacienteEspecial = $paciente->pacienteEspecial;
            
            if ($pacienteEspecial && $pacienteEspecial->representante) {
                // Es paciente especial: notificar al representante
                $representante = $pacienteEspecial->representante;
                $pacienteRepresentante = \App\Models\Paciente::where('tipo_documento', $representante->tipo_documento)
                                          ->where('numero_documento', $representante->numero_documento)
                                          ->first();
                
                if ($pacienteRepresentante) {
                    $pacienteRepresentante->notify(new PagoRechazado($pago, $request->motivo));
                }
            } else {
                // Paciente regular: notificar directamente
                $paciente->notify(new PagoRechazado($pago, $request->motivo));
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pago rechazado exitosamente']);
        }

        return redirect()->back()->with('success', 'Pago rechazado exitosamente');
    }

    private function getEstadoInicial($metodoPagoId)
    {
        $metodo = MetodoPago::find($metodoPagoId);
        
        if ($metodo && $metodo->requiere_confirmacion) {
            return 'Pendiente';
        }
        
        return 'Confirmado';
    }

    private function actualizarEstadoFactura($facturaId)
    {
        $factura = FacturaPaciente::findOrFail($facturaId);
        $totalPagado = Pago::where('id_factura_paciente', $facturaId)
                          ->where('status', true)
                          ->where('estado', 'Confirmado')
                          ->sum('monto_equivalente_usd');

        $tolerancia = 0.01; // Tolerancia para comparaciones de decimales

        if (abs($totalPagado - $factura->monto_usd) < $tolerancia) {
            $factura->update(['status_factura' => 'Pagada']);
        } else if ($totalPagado > 0) {
            $factura->update(['status_factura' => 'Parcialmente Pagada']);
        } else {
            $factura->update(['status_factura' => 'Emitida']);
        }
    }

    private function enviarNotificacionPago($pago)
    {
        try {
            $pago->load(['facturaPaciente.cita.paciente.usuario', 'facturaPaciente.cita.paciente.pacienteEspecial.representante']);
            
            $paciente = $pago->facturaPaciente->cita->paciente;
            $pacienteEspecial = $paciente->pacienteEspecial;
            
            // Determinar el correo del destinatario
            $correoDestinatario = null;
            
            if ($pacienteEspecial && $pacienteEspecial->representante) {
                // Es paciente especial: enviar correo al representante
                $representante = $pacienteEspecial->representante;
                $pacienteRepresentante = \App\Models\Paciente::where('tipo_documento', $representante->tipo_documento)
                                          ->where('numero_documento', $representante->numero_documento)
                                          ->first();
                
                if ($pacienteRepresentante && $pacienteRepresentante->usuario) {
                    $correoDestinatario = $pacienteRepresentante->usuario->correo;
                }
            } else {
                // Paciente regular
                if ($paciente->usuario) {
                    $correoDestinatario = $paciente->usuario->correo;
                }
            }
            
            if ($correoDestinatario) {
                Mail::send('emails.confirmacion-pago', ['pago' => $pago], function($message) use ($correoDestinatario, $pago) {
                    $message->to($correoDestinatario)
                            ->subject('Confirmación de Pago - Factura #' . $pago->facturaPaciente->numero_factura);
                });
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de pago: ' . $e->getMessage());
        }
    }

    public function reportePagos(Request $request)
    {
        $query = Pago::with(['facturaPaciente.cita.paciente', 'metodoPago'])
                    ->where('status', true);

        if ($request->has('fecha_inicio') && $request->fecha_inicio) {
            $query->whereDate('fecha_pago', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin') && $request->fecha_fin) {
            $query->whereDate('fecha_pago', '<=', $request->fecha_fin);
        }

        if ($request->has('metodo_pago') && $request->metodo_pago) {
            $query->where('id_metodo', $request->metodo_pago);
        }

        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }

        $pagos = $query->get();

        return view('shared.pagos.reporte', compact('pagos'));
    }

    // Para pacientes - Ver sus pagos
    public function misPagos()
    {
        $user = Auth::user();
        if (!$user->paciente) {
            abort(403, 'Acceso no autorizado');
        }

        $pagos = Pago::with(['facturaPaciente.cita.medico', 'metodoPago'])
                    ->whereHas('facturaPaciente', function($query) use ($user) {
                        $query->where('paciente_id', $user->paciente->id);
                    })
                    ->where('status', true)
                    ->paginate(10);

        return view('shared.pagos.mis-pagos', compact('pagos'));
    }

    // Para pacientes - Mostrar formulario de registro de pago
    public function mostrarRegistroPago($citaId)
    {
        $user = Auth::user();
        if (!$user->paciente) {
            abort(403, 'Acceso no autorizado');
        }

        $cita = \App\Models\Cita::with(['medico', 'especialidad', 'consultorio', 'paciente', 'facturaPaciente.pagos'])
                    ->findOrFail($citaId);

        // Verificar que la cita pertenezca al paciente o a un representado
        $paciente = $user->paciente;
        $esPropia = $cita->paciente_id == $paciente->id;
        
        $esTercero = false;
        if (!$esPropia && $cita->paciente_especial_id) {
            $representante = \App\Models\Representante::where('numero_documento', $paciente->numero_documento)
                                        ->where('tipo_documento', $paciente->tipo_documento)
                                        ->first();
            if ($representante) {
                $esTercero = \DB::table('representante_paciente_especial')
                            ->where('representante_id', $representante->id)
                            ->where('paciente_especial_id', $cita->paciente_especial_id)
                            ->exists();
            }
        }

        if (!$esPropia && !$esTercero) {
            abort(403, 'No tiene permisos para registrar el pago de esta cita');
        }

        // Verificar que la cita esté en estado Programada
        if ($cita->estado_cita != 'Programada') {
            return redirect()->route('paciente.citas.show', $citaId)
                           ->with('error', 'Solo puede registrar pagos para citas en estado Programada');
        }

        // Verificar si ya existe un pago pendiente o confirmado
        if ($cita->facturaPaciente && $cita->facturaPaciente->pagos()->where('status', true)->count() > 0) {
            $ultimoPago = $cita->facturaPaciente->pagos()->where('status', true)->orderBy('created_at', 'desc')->first();
            
            // Solo bloquear si hay un pago Pendiente o Confirmado
            // Permitir re-submission si el último pago fue Rechazado
            if (in_array($ultimoPago->estado, ['Pendiente', 'Confirmado'])) {
                return redirect()->route('paciente.citas.show', $citaId)
                               ->with('info', 'Esta cita ya tiene un pago ' . strtolower($ultimoPago->estado));
            }
        }

        $metodosPago = \App\Models\MetodoPago::where('status', true)->get();
        $tasaActual = \App\Models\TasaDolar::where('status', true)
                                           ->orderBy('fecha_tasa', 'desc')
                                           ->first();

        if (!$tasaActual) {
            return redirect()->route('paciente.citas.show', $citaId)
                           ->with('error', 'No se encuentra una tasa de cambio configurada. Contacte al administrador.');
        }

        return view('paciente.pagos.registrar', compact('cita', 'metodosPago', 'tasaActual'));
    }

    // Para pacientes - Registrar nuevo pago
    public function registrarPagoPaciente(Request $request)
    {
        $user = Auth::user();
        if (!$user->paciente) {
            abort(403, 'Acceso no autorizado');
        }

        $validator = Validator::make($request->all(), [
            'cita_id' => 'required|exists:citas,id',
            'id_metodo' => 'required|exists:metodo_pago,id_metodo',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'monto_pagado_bs' => 'required|numeric|min:0',
            'tasa_aplicada_id' => 'required|exists:tasas_dolar,id',
            'referencia' => 'required|max:255',
            'comentarios' => 'nullable|string'
        ], [
            'cita_id.required' => 'Debe seleccionar una cita',
            'id_metodo.required' => 'Debe seleccionar un método de pago',
            'fecha_pago.required' => 'La fecha de pago es requerida',
            'fecha_pago.before_or_equal' => 'La fecha de pago no puede ser futura',
            'monto_pagado_bs.required' => 'El monto es requerido',
            'monto_pagado_bs.min' => 'El monto debe ser mayor a 0',
            'referencia.required' => 'La referencia es requerida'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \DB::beginTransaction();

            $cita = \App\Models\Cita::with(['medico', 'especialidad'])->findOrFail($request->cita_id);
            
            // Verificar permisos
            $paciente = $user->paciente;
            $esPropia = $cita->paciente_id == $paciente->id;
            $esTercero = false;
            
            if (!$esPropia && $cita->paciente_especial_id) {
                $representante = \App\Models\Representante::where('numero_documento', $paciente->numero_documento)
                                            ->where('tipo_documento', $paciente->tipo_documento)
                                            ->first();
                if ($representante) {
                    $esTercero = \DB::table('representante_paciente_especial')
                                ->where('representante_id', $representante->id)
                                ->where('paciente_especial_id', $cita->paciente_especial_id)
                                ->exists();
                }
            }

            if (!$esPropia && !$esTercero) {
                throw new \Exception('No tiene permisos para registrar el pago de esta cita');
            }

            // Verificar estado de la cita
            if ($cita->estado_cita != 'Programada') {
                throw new \Exception('Solo puede registrar pagos para citas en estado Programada');
            }

            $tasa = \App\Models\TasaDolar::findOrFail($request->tasa_aplicada_id);
            $montoEquivalenteUSD = $request->monto_pagado_bs / $tasa->valor;

            // Manejo de archivo comprobante
            $comprobantePath = null;
            if ($request->hasFile('comprobante')) {
                $file = $request->file('comprobante');
                $filename = time() . '_' . $file->getClientOriginalName();
                $comprobantePath = $file->storeAs('comprobantes_pagos', $filename, 'public');
            }

            // Crear o verificar factura del paciente
            $factura = $cita->facturaPaciente;
            
            if (!$factura) {
                // Crear factura
                $numeroFactura = 'FACT-' . date('Y') . '-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT);
                
                $factura = FacturaPaciente::create([
                    'cita_id' => $cita->id,
                    'paciente_id' => $cita->paciente_id,
                    'medico_id' => $cita->medico_id,
                    'monto_usd' => $cita->tarifa + $cita->tarifa_extra,
                    'tasa_id' => $tasa->id,
                    'monto_bs' => ($cita->tarifa + $cita->tarifa_extra) * $tasa->valor,
                    'fecha_emision' => now(),
                    'numero_factura' => $numeroFactura,
                    'status_factura' => 'Emitida',
                    'status' => true
                ]);
            }

            // Deactivar pagos rechazados anteriores para esta factura
            // Esto mantiene el historial limpio y solo muestra el intento activo
            if ($factura) {
                Pago::where('id_factura_paciente', $factura->id)
                    ->where('estado', 'Rechazado')
                    ->where('status', true)
                    ->update(['status' => false]);
            }

            // Crear el pago con estado Pendiente
            $pago = Pago::create([
                'id_factura_paciente' => $factura->id,
                'id_metodo' => $request->id_metodo,
                'fecha_pago' => $request->fecha_pago,
                'monto_pagado_bs' => $request->monto_pagado_bs,
                'monto_equivalente_usd' => $montoEquivalenteUSD,
                'tasa_aplicada_id' => $tasa->id,
                'referencia' => $request->referencia,
                'comprobante' => $comprobantePath,
                'comentarios' => $request->comentarios,
                'estado' => 'Pendiente', // Siempre pendiente para revisión del admin
                'status' => true
            ]);

            \DB::commit();

            // Notificar a los administradores relevantes
            try {
                $consultorioId = $cita->consultorio_id;
                $admins = \App\Models\Administrador::where('status', true)->get();
                
                foreach ($admins as $admin) {
                    // Root admins ven todo
                    if ($admin->tipo_admin === 'Root') {
                        $admin->notify(new \App\Notifications\Admin\NuevoPagoRegistrado($pago));
                    }
                    // Local admins solo si es su consultorio
                    elseif ($admin->tipo_admin === 'Administrador' && $consultorioId) {
                        $tieneAcceso = \DB::table('administrador_consultorio')
                            ->where('administrador_id', $admin->id)
                            ->where('consultorio_id', $consultorioId)
                            ->exists();
                        
                        if ($tieneAcceso) {
                            $admin->notify(new \App\Notifications\Admin\NuevoPagoRegistrado($pago));
                        }
                    }
                }
            } catch (\Exception $ne) {
                \Log::error('Error enviando notificación de pago: ' . $ne->getMessage());
            }

            return redirect()->route('paciente.citas.show', $cita->id)
                           ->with('success', '¡Pago registrado exitosamente! Su pago será revisado por nuestro equipo y recibirá una notificación cuando sea confirmado.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al registrar pago del paciente: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', $e->getMessage())
                           ->withInput();
        }
    }
}
