<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\FacturaPaciente;
use App\Models\MetodoPago;
use App\Models\TasaDolar;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['facturaPaciente.cita.paciente', 'metodoPago', 'confirmadoPor'])
                    ->where('status', true)
                    ->paginate(10);
        
        return view('shared.pagos.index', compact('pagos'));
    }

    public function create()
    {
        $facturas = FacturaPaciente::where('status_factura', 'Emitida')
                                  ->where('status', true)
                                  ->get();
        
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
        $validator = Validator::make($request->all(), [
            'confirmado_por' => 'required|exists:administradores,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $pago = Pago::findOrFail($id);
        
        $pago->update([
            'estado' => 'Confirmado',
            'confirmado_por' => $request->confirmado_por
        ]);

        // Actualizar estado de la factura
        $this->actualizarEstadoFactura($pago->id_factura_paciente);

        // Enviar notificación
        $this->enviarNotificacionPago($pago);

        return redirect()->back()->with('success', 'Pago confirmado exitosamente');
    }

    public function rechazarPago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $pago = Pago::findOrFail($id);
        
        $pago->update([
            'estado' => 'Rechazado',
            'comentarios' => $request->motivo . ' - ' . ($pago->comentarios ?? '')
        ]);

        // Actualizar estado de la factura
        $this->actualizarEstadoFactura($pago->id_factura_paciente);

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
            $pago->load(['facturaPaciente.cita.paciente.usuario']);
            
            Mail::send('emails.confirmacion-pago', ['pago' => $pago], function($message) use ($pago) {
                $message->to($pago->facturaPaciente->cita->paciente->usuario->correo)
                        ->subject('Confirmación de Pago - Factura #' . $pago->facturaPaciente->numero_factura);
            });
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
}
