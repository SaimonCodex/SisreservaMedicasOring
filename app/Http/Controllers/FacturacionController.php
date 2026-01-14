<?php

namespace App\Http\Controllers;

use App\Models\FacturaPaciente;
use App\Models\FacturaCabecera;
use App\Models\FacturaDetalle;
use App\Models\FacturaTotal;
use App\Models\Cita;
use App\Models\TasaDolar;
use App\Models\ConfiguracionReparto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class FacturacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = FacturaPaciente::with(['cita.paciente', 'cita.medico', 'tasa'])
                                  ->where('status', true);

        if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios()->pluck('consultorios.id');
            $query->whereHas('cita', function($q) use ($consultorioIds) {
                $q->whereIn('consultorio_id', $consultorioIds);
            });
        }

        $facturas = $query->paginate(10);
        return view('shared.facturacion.index', compact('facturas'));
    }

    public function create()
    {
        $user = auth()->user();
        $query = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio'])
                     ->whereDoesntHave('facturaPaciente')
                     ->where('estado_cita', 'Completada')
                     ->where('status', true);

        if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios()->pluck('consultorios.id');
            $query->whereIn('consultorio_id', $consultorioIds);
        }

        $citas = $query->get();
        
        $tasas = TasaDolar::where('status', true)
                          ->orderBy('fecha_tasa', 'desc')
                          ->get();
        
        return view('shared.facturacion.create', compact('citas', 'tasas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cita_id' => 'required|exists:citas,id|unique:facturas_pacientes,cita_id',
            'tasa_id' => 'required|exists:tasas_dolar,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'numero_factura' => 'nullable|unique:facturas_pacientes,numero_factura'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cita = Cita::with(['medico', 'consultorio'])->findOrFail($request->cita_id);
        $tasa = TasaDolar::findOrFail($request->tasa_id);

        // Calcular monto en bolívares
        $montoBS = $cita->tarifa * $tasa->valor;

        // Generar número de factura si no se proporcionó uno
        $numeroFactura = $request->numero_factura;
        if (!$numeroFactura) {
            $year = date('Y');
            $count = FacturaPaciente::whereYear('fecha_emision', $year)->count() + 1;
            $numeroFactura = 'FAC-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
        }

        $factura = FacturaPaciente::create([
            'cita_id' => $cita->id,
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $cita->medico_id,
            'monto_usd' => $cita->tarifa,
            'tasa_id' => $tasa->id,
            'monto_bs' => $montoBS,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'numero_factura' => $numeroFactura,
            'status_factura' => 'Emitida',
            'status' => true
        ]);

        // Crear facturación avanzada (cabecera y detalles)
        $this->crearFacturacionAvanzada($factura, $cita, $tasa);

        return redirect()->route('facturacion.show', $factura->id)->with('success', 'Factura creada exitosamente');
    }

    public function show($id)
    {
        $factura = FacturaPaciente::with([
            'cita.paciente', 
            'cita.medico', 
            'cita.especialidad',
            'tasa',
            'pagos'
        ])->findOrFail($id);

        return view('shared.facturacion.show', compact('factura'));
    }

    public function edit($id)
    {
        $factura = FacturaPaciente::findOrFail($id);
        $tasas = TasaDolar::where('status', true)->orderBy('fecha_tasa', 'desc')->get();
        return view('shared.facturacion.edit', compact('factura', 'tasas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tasa_id' => 'required|exists:tasas_dolar,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'numero_factura' => 'nullable|unique:facturas_pacientes,numero_factura,' . $id,
            'status_factura' => 'required|in:Emitida,Pagada,Anulada,Vencida'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $factura = FacturaPaciente::findOrFail($id);
        $tasa = TasaDolar::findOrFail($request->tasa_id);

        // Recalcular monto en bolívares
        $montoBS = $factura->monto_usd * $tasa->valor;

        $factura->update([
            'tasa_id' => $tasa->id,
            'monto_bs' => $montoBS,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'numero_factura' => $request->numero_factura,
            'status_factura' => $request->status_factura
        ]);

        return redirect()->route('facturacion.index')->with('success', 'Factura actualizada exitosamente');
    }

    public function destroy($id)
    {
        $factura = FacturaPaciente::findOrFail($id);
        $factura->update(['status' => false]);

        return redirect()->route('facturacion.index')->with('success', 'Factura eliminada exitosamente');
    }

    public function enviarRecordatorio($id)
    {
        $factura = FacturaPaciente::with(['cita.paciente.usuario'])->findOrFail($id);
        
        try {
            Mail::send('emails.recordatorio-pago', ['factura' => $factura], function($message) use ($factura) {
                $message->to($factura->cita->paciente->usuario->correo)
                        ->subject('Recordatorio de Pago - Factura #' . $factura->numero_factura);
            });
            
            return redirect()->back()->with('success', 'Recordatorio enviado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al enviar el recordatorio: ' . $e->getMessage());
        }
    }

    // Facturación avanzada - Sistema de reparto
    private function crearFacturacionAvanzada($facturaPaciente, $cita, $tasa)
    {
        // Crear cabecera de factura avanzada
        $facturaCabecera = FacturaCabecera::create([
            'cita_id' => $cita->id,
            'nro_control' => $this->generarNumeroControl(),
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $cita->medico_id,
            'tasa_id' => $tasa->id,
            'fecha_emision' => now(),
            'status' => true
        ]);

        // Obtener configuración de reparto
        $configReparto = ConfiguracionReparto::where('medico_id', $cita->medico_id)
                                            ->where('consultorio_id', $cita->consultorio_id)
                                            ->first();

        if (!$configReparto) {
            // Usar configuración por defecto (sin consultorio específico)
            $configReparto = ConfiguracionReparto::where('medico_id', $cita->medico_id)
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
        $sequence = FacturaCabecera::whereYear('fecha_emision', $year)->count() + 1;
        return 'FACT-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    private function crearDetallesFactura($facturaCabecera, $cita, $configReparto)
    {
        $tarifaUSD = $cita->tarifa;

        // Detalle para el médico
        FacturaDetalle::create([
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
            FacturaDetalle::create([
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
            FacturaDetalle::create([
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
            $totalFinalUSD = $baseImponibleUSD; // Asumiendo que no hay impuestos por ahora
            $totalFinalBS = $totalFinalUSD * $tasa->valor;

            FacturaTotal::create([
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



    public function crearLiquidacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entidad_tipo' => 'required|in:Medico,Consultorio',
            'entidad_id' => 'required',
            'metodo_pago' => 'required|in:Transferencia,Zelle,Efectivo,Pago Movil,Otro',
            'referencia' => 'required|max:100',
            'fecha_pago' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener facturas pendientes de liquidación para la entidad
        $facturasTotales = FacturaTotal::where('entidad_tipo', $request->entidad_tipo)
                                      ->where('entidad_id', $request->entidad_id)
                                      ->where('estado_liquidacion', 'Pendiente')
                                      ->where('status', true)
                                      ->get();

        if ($facturasTotales->isEmpty()) {
            return redirect()->back()->with('error', 'No hay facturas pendientes de liquidación para esta entidad');
        }

        $montoTotalUSD = $facturasTotales->sum('total_final_usd');
        $montoTotalBS = $facturasTotales->sum('total_final_bs');

        // Crear liquidación
        $liquidacion = \App\Models\Liquidacion::create([
            'entidad_tipo' => $request->entidad_tipo,
            'entidad_id' => $request->entidad_id,
            'monto_total_usd' => $montoTotalUSD,
            'monto_total_bs' => $montoTotalBS,
            'metodo_pago' => $request->metodo_pago,
            'referencia' => $request->referencia,
            'fecha_pago' => $request->fecha_pago,
            'observaciones' => $request->observaciones,
            'status' => true
        ]);

        // Crear detalles de liquidación
        foreach ($facturasTotales as $facturaTotal) {
            \App\Models\LiquidacionDetaille::create([
                'liquidacion_id' => $liquidacion->id,
                'factura_total_id' => $facturaTotal->id,
                'status' => true
            ]);

            // Marcar factura como liquidada
            $facturaTotal->update(['estado_liquidacion' => 'Liquidado']);
        }

        return redirect()->route('facturacion.liquidaciones')->with('success', 'Liquidación creada exitosamente');
    }

    /**
     * Mostrar vista de liquidaciones con totales pendientes
     */
    public function liquidaciones()
    {
        $user = auth()->user();
        $isLocalAdmin = $user && $user->administrador && $user->administrador->tipo_admin !== 'Root';
        $consultorioIds = [];

        if ($isLocalAdmin) {
            $consultorioIds = $user->administrador->consultorios()->pluck('consultorios.id');
        }

        // Obtener totales pendientes agrupados por entidad
        $query = FacturaTotal::with(['medico', 'consultorio'])
                                        ->where('estado_liquidacion', 'Pendiente')
                                        ->where('status', true);

        if ($isLocalAdmin) {
            $query->where(function($q) use ($consultorioIds) {
                // Liquidaciones para sus consultorios
                $q->where(function($sq) use ($consultorioIds) {
                    $sq->where('entidad_tipo', 'Consultorio')
                       ->whereIn('entidad_id', $consultorioIds);
                })
                // O liquidaciones para medicos que trabajan en sus consultorios
                ->orWhere(function($sq) use ($consultorioIds) {
                    $sq->where('entidad_tipo', 'Medico')
                       ->whereHas('medico.consultorios', function($ssq) use ($consultorioIds) {
                           $ssq->whereIn('consultorios.id', $consultorioIds);
                       });
                });
            });
        }

        $totalesPendientes = $query->get();

        // Calcular totales por tipo de entidad
        $totalesPorEntidad = [
            'Medico' => $totalesPendientes->where('entidad_tipo', 'Medico')->sum('total_final_usd'),
            'Consultorio' => $totalesPendientes->where('entidad_tipo', 'Consultorio')->sum('total_final_usd'),
            'Sistema' => $isLocalAdmin ? 0 : $totalesPendientes->where('entidad_tipo', 'Sistema')->sum('total_final_usd'),
        ];

        return view('shared.facturacion.liquidaciones', compact('totalesPendientes', 'totalesPorEntidad'));
    }
}
