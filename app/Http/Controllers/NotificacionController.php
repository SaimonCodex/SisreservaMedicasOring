<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    // =========================================================================
    // LISTADO Y GESTIÓN DE NOTIFICACIONES
    // =========================================================================

    public function index()
    {
        $user = Auth::user();
        $query = Notificacion::where('status', true);

        // Filtrar por usuario según su rol
        if ($user->rol_id == 2) { // Médico
            $medico = $user->medico;
            $query->where('receptor_rol', 'Medico')
                  ->where('receptor_id', $medico->id);
        } elseif ($user->rol_id == 3) { // Paciente
            $paciente = $user->paciente;
            $query->where('receptor_rol', 'Paciente')
                  ->where('receptor_id', $paciente->id);
        } elseif ($user->rol_id == 1) { // Admin
            $query->whereIn('receptor_rol', ['Admin', 'Root', 'Sistema']);
        }

        $notificaciones = $query->orderBy('created_at', 'desc')
                              ->paginate(20);

        return view('shared.notificaciones.index', compact('notificaciones'));
    }

    public function create()
    {
        $tipos = [
            'Recordatorio_Cita' => 'Recordatorio de Cita',
            'Pago_Aprobado' => 'Pago Aprobado',
            'Pago_Rechazado' => 'Pago Rechazado',
            'Cancelacion' => 'Cancelación',
            'Alerta_Adm' => 'Alerta Administrativa',
            'Sistema' => 'Notificación del Sistema'
        ];

        $vias = ['Correo', 'Sistema', 'WhatsApp', 'SMS', 'Multiple'];
        $roles = ['Paciente', 'Medico', 'Admin', 'Root'];

        return view('shared.notificaciones.create', compact('tipos', 'vias', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receptor_rol' => 'required|in:Paciente,Medico,Admin,Root',
            'receptor_id' => 'required_if:receptor_rol,Paciente,Medico,Admin,Root',
            'tipo' => 'required|in:Recordatorio_Cita,Pago_Aprobado,Pago_Rechazado,Cancelacion,Alerta_Adm,Sistema',
            'titulo' => 'required|string|max:150',
            'mensaje' => 'required|string',
            'via' => 'required|in:Correo,Sistema,WhatsApp,SMS,Multiple'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determinar el receptor_id si se envía a todos los usuarios de un rol
        if ($request->receptor_rol == 'Todos') {
            $receptorId = 0; // Valor especial para indicar "todos"
        } else {
            $receptorId = $request->receptor_id;
        }

        $notificacion = Notificacion::create([
            'receptor_id' => $receptorId,
            'receptor_rol' => $request->receptor_rol,
            'tipo' => $request->tipo,
            'titulo' => $request->titulo,
            'mensaje' => $request->mensaje,
            'via' => $request->via,
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        // Enviar la notificación según la vía seleccionada
        $this->enviarNotificacion($notificacion);

        return redirect()->route('notificaciones.index')->with('success', 'Notificación creada y enviada exitosamente');
    }

    public function show($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        
        // Marcar como leída si el usuario actual es el receptor
        $user = Auth::user();
        if ($this->esReceptor($notificacion, $user)) {
            $notificacion->update(['estado_envio' => 'Leido']);
        }

        return view('shared.notificaciones.show', compact('notificacion'));
    }

    public function edit($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $tipos = [
            'Recordatorio_Cita' => 'Recordatorio de Cita',
            'Pago_Aprobado' => 'Pago Aprobado',
            'Pago_Rechazado' => 'Pago Rechazado',
            'Cancelacion' => 'Cancelación',
            'Alerta_Adm' => 'Alerta Administrativa',
            'Sistema' => 'Notificación del Sistema'
        ];

        $vias = ['Correo', 'Sistema', 'WhatsApp', 'SMS', 'Multiple'];
        $roles = ['Paciente', 'Medico', 'Admin', 'Root'];

        return view('shared.notificaciones.edit', compact('notificacion', 'tipos', 'vias', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:Recordatorio_Cita,Pago_Aprobado,Pago_Rechazado,Cancelacion,Alerta_Adm,Sistema',
            'titulo' => 'required|string|max:150',
            'mensaje' => 'required|string',
            'via' => 'required|in:Correo,Sistema,WhatsApp,SMS,Multiple',
            'estado_envio' => 'required|in:Pendiente,Enviado,Fallido,Leido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($request->all());

        return redirect()->route('notificaciones.index')->with('success', 'Notificación actualizada exitosamente');
    }

    public function destroy($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update(['status' => false]);

        return redirect()->route('notificaciones.index')->with('success', 'Notificación eliminada exitosamente');
    }

    // =========================================================================
    // ENVÍO Y REENVÍO DE NOTIFICACIONES
    // =========================================================================

    public function reenviar($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        
        try {
            $this->enviarNotificacion($notificacion);
            $notificacion->update(['estado_envio' => 'Enviado']);
            
            return redirect()->back()->with('success', 'Notificación reenviada exitosamente');
        } catch (\Exception $e) {
            $notificacion->update([
                'estado_envio' => 'Fallido',
                'error_detalle' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error al reenviar: ' . $e->getMessage());
        }
    }

    public function enviarMasivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receptor_rol' => 'required|in:Paciente,Medico,Admin,Root',
            'tipo' => 'required|in:Recordatorio_Cita,Pago_Aprobado,Pago_Rechazado,Cancelacion,Alerta_Adm,Sistema',
            'titulo' => 'required|string|max:150',
            'mensaje' => 'required|string',
            'via' => 'required|in:Correo,Sistema,WhatsApp,SMS,Multiple'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener todos los usuarios del rol especificado
        $usuarios = $this->obtenerUsuariosPorRol($request->receptor_rol);
        
        $enviados = 0;
        $fallidos = 0;

        foreach ($usuarios as $usuario) {
            try {
                $notificacion = Notificacion::create([
                    'receptor_id' => $usuario->id,
                    'receptor_rol' => $request->receptor_rol,
                    'tipo' => $request->tipo,
                    'titulo' => $request->titulo,
                    'mensaje' => $request->mensaje,
                    'via' => $request->via,
                    'estado_envio' => 'Enviado',
                    'status' => true
                ]);

                $this->enviarNotificacionIndividual($notificacion, $usuario);
                $enviados++;
            } catch (\Exception $e) {
                $fallidos++;
                \Log::error('Error enviando notificación masiva: ' . $e->getMessage());
            }
        }

        return redirect()->route('notificaciones.index')
                       ->with('success', "Notificación masiva enviada: {$enviados} exitosos, {$fallidos} fallidos");
    }

    // =========================================================================
    // NOTIFICACIONES AUTOMÁTICAS DEL SISTEMA
    // =========================================================================

    public function enviarRecordatorioCita(Cita $cita)
    {
        $paciente = $cita->paciente;
        $usuario = $paciente->usuario;

        $notificacion = Notificacion::create([
            'receptor_id' => $paciente->id,
            'receptor_rol' => 'Paciente',
            'tipo' => 'Recordatorio_Cita',
            'titulo' => 'Recordatorio de Cita Médica',
            'mensaje' => "Tiene una cita programada para el {$cita->fecha_cita} a las {$cita->hora_inicio} con el Dr. {$cita->medico->primer_nombre} {$cita->medico->primer_apellido}",
            'via' => 'Multiple',
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        $this->enviarNotificacion($notificacion);
    }

    public function enviarConfirmacionPago($pago)
    {
        $factura = $pago->facturaPaciente;
        $cita = $factura->cita;
        $paciente = $cita->paciente;

        $notificacion = Notificacion::create([
            'receptor_id' => $paciente->id,
            'receptor_rol' => 'Paciente',
            'tipo' => 'Pago_Aprobado',
            'titulo' => 'Confirmación de Pago',
            'mensaje' => "Su pago de {$pago->monto_pagado_bs} Bs. ha sido confirmado. Referencia: {$pago->referencia}",
            'via' => 'Multiple',
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        $this->enviarNotificacion($notificacion);
    }

    public function enviarAlertaSistema($titulo, $mensaje, $rolDestino = 'Admin')
    {
        $notificacion = Notificacion::create([
            'receptor_id' => 0, // Para todos los admins
            'receptor_rol' => $rolDestino,
            'tipo' => 'Alerta_Adm',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'via' => 'Sistema',
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        $this->enviarNotificacion($notificacion);
    }

    // =========================================================================
    // MÉTODOS PRIVADOS DE ENVÍO
    // =========================================================================

    private function enviarNotificacion(Notificacion $notificacion)
    {
        try {
            switch ($notificacion->via) {
                case 'Correo':
                    $this->enviarPorCorreo($notificacion);
                    break;
                case 'Sistema':
                    // Notificación interna del sistema (solo se marca como enviada)
                    $notificacion->update(['estado_envio' => 'Enviado']);
                    break;
                case 'WhatsApp':
                    $this->enviarPorWhatsApp($notificacion);
                    break;
                case 'SMS':
                    $this->enviarPorSMS($notificacion);
                    break;
                case 'Multiple':
                    $this->enviarPorCorreo($notificacion);
                    $this->enviarPorWhatsApp($notificacion);
                    $notificacion->update(['estado_envio' => 'Enviado']);
                    break;
            }
        } catch (\Exception $e) {
            $notificacion->update([
                'estado_envio' => 'Fallido',
                'error_detalle' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function enviarNotificacionIndividual(Notificacion $notificacion, $usuario)
    {
        try {
            switch ($notificacion->via) {
                case 'Correo':
                    $this->enviarCorreoIndividual($notificacion, $usuario);
                    break;
                case 'WhatsApp':
                    $this->enviarWhatsAppIndividual($notificacion, $usuario);
                    break;
                default:
                    // Para otros métodos, solo marcamos como enviado
                    $notificacion->update(['estado_envio' => 'Enviado']);
                    break;
            }
        } catch (\Exception $e) {
            $notificacion->update([
                'estado_envio' => 'Fallido',
                'error_detalle' => $e->getMessage()
            ]);
        }
    }

    private function enviarPorCorreo(Notificacion $notificacion)
    {
        $destinatario = $this->obtenerDestinatario($notificacion);
        
        if (!$destinatario || !$destinatario->correo) {
            throw new \Exception('No se puede enviar correo: destinatario no válido');
        }

        Mail::send('emails.notificacion', ['notificacion' => $notificacion], function($message) use ($destinatario, $notificacion) {
            $message->to($destinatario->correo)
                    ->subject($notificacion->titulo);
        });

        $notificacion->update(['estado_envio' => 'Enviado']);
    }

    private function enviarCorreoIndividual(Notificacion $notificacion, $usuario)
    {
        if (!$usuario->correo) {
            throw new \Exception('Usuario no tiene correo electrónico');
        }

        Mail::send('emails.notificacion', ['notificacion' => $notificacion], function($message) use ($usuario, $notificacion) {
            $message->to($usuario->correo)
                    ->subject($notificacion->titulo);
        });

        $notificacion->update(['estado_envio' => 'Enviado']);
    }

    private function enviarPorWhatsApp(Notificacion $notificacion)
    {
        // Implementación de envío por WhatsApp (requiere integración con API)
        // Por ahora solo simulamos el envío
        \Log::info("WhatsApp enviado: {$notificacion->titulo} - {$notificacion->mensaje}");
        $notificacion->update(['estado_envio' => 'Enviado']);
    }

    private function enviarWhatsAppIndividual(Notificacion $notificacion, $usuario)
    {
        // Implementación individual de WhatsApp
        \Log::info("WhatsApp individual enviado a {$usuario->correo}: {$notificacion->titulo}");
        $notificacion->update(['estado_envio' => 'Enviado']);
    }

    private function enviarPorSMS(Notificacion $notificacion)
    {
        // Implementación de envío por SMS (requiere integración con API)
        \Log::info("SMS enviado: {$notificacion->titulo} - {$notificacion->mensaje}");
        $notificacion->update(['estado_envio' => 'Enviado']);
    }

    // =========================================================================
    // MÉTODOS AUXILIARES
    // =========================================================================

    private function obtenerDestinatario(Notificacion $notificacion)
    {
        switch ($notificacion->receptor_rol) {
            case 'Paciente':
                $paciente = Paciente::find($notificacion->receptor_id);
                return $paciente ? $paciente->usuario : null;
            case 'Medico':
                $medico = Medico::find($notificacion->receptor_id);
                return $medico ? $medico->usuario : null;
            case 'Admin':
            case 'Root':
                $admin = Administrador::find($notificacion->receptor_id);
                return $admin ? $admin->usuario : null;
            default:
                return null;
        }
    }

    private function obtenerUsuariosPorRol($rol)
    {
        switch ($rol) {
            case 'Paciente':
                return Usuario::where('rol_id', 3)->where('status', true)->get();
            case 'Medico':
                return Usuario::where('rol_id', 2)->where('status', true)->get();
            case 'Admin':
                return Usuario::where('rol_id', 1)->where('status', true)->get();
            default:
                return collect();
        }
    }

    private function esReceptor(Notificacion $notificacion, $user)
    {
        if ($notificacion->receptor_rol == 'Paciente' && $user->paciente) {
            return $notificacion->receptor_id == $user->paciente->id;
        }
        if ($notificacion->receptor_rol == 'Medico' && $user->medico) {
            return $notificacion->receptor_id == $user->medico->id;
        }
        if (in_array($notificacion->receptor_rol, ['Admin', 'Root']) && $user->administrador) {
            return $notificacion->receptor_id == $user->administrador->id;
        }
        return false;
    }

    // =========================================================================
    // REPORTES Y ESTADÍSTICAS
    // =========================================================================

    public function reporteNotificaciones(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'tipo' => 'nullable|in:Recordatorio_Cita,Pago_Aprobado,Pago_Rechazado,Cancelacion,Alerta_Adm,Sistema',
            'via' => 'nullable|in:Correo,Sistema,WhatsApp,SMS,Multiple',
            'estado_envio' => 'nullable|in:Pendiente,Enviado,Fallido,Leido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = Notificacion::where('status', true);

        if ($request->fecha_inicio) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->via) {
            $query->where('via', $request->via);
        }

        if ($request->estado_envio) {
            $query->where('estado_envio', $request->estado_envio);
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->get();

        $estadisticas = [
            'total' => $notificaciones->count(),
            'enviadas' => $notificaciones->where('estado_envio', 'Enviado')->count(),
            'fallidas' => $notificaciones->where('estado_envio', 'Fallido')->count(),
            'pendientes' => $notificaciones->where('estado_envio', 'Pendiente')->count(),
            'leidas' => $notificaciones->where('estado_envio', 'Leido')->count()
        ];

        return view('shared.notificaciones.reporte', compact('notificaciones', 'estadisticas'))->with('filtros', $request->all());
    }

    public function estadisticas()
    {
        $totalNotificaciones = Notificacion::where('status', true)->count();
        
        $porTipo = Notificacion::select('tipo')
                             ->selectRaw('COUNT(*) as total')
                             ->where('status', true)
                             ->groupBy('tipo')
                             ->get();

        $porVia = Notificacion::select('via')
                            ->selectRaw('COUNT(*) as total')
                            ->where('status', true)
                            ->groupBy('via')
                            ->get();

        $porEstado = Notificacion::select('estado_envio')
                               ->selectRaw('COUNT(*) as total')
                               ->where('status', true)
                               ->groupBy('estado_envio')
                               ->get();

        $porMes = Notificacion::selectRaw('YEAR(created_at) as año, MONTH(created_at) as mes, COUNT(*) as total')
                            ->where('status', true)
                            ->where('created_at', '>=', now()->subYear())
                            ->groupBy('año', 'mes')
                            ->orderBy('año', 'desc')
                            ->orderBy('mes', 'desc')
                            ->get();

        return view('shared.notificaciones.estadisticas', compact(
            'totalNotificaciones',
            'porTipo',
            'porVia',
            'porEstado',
            'porMes'
        ));
    }

    // =========================================================================
    // LIMPIEZA DE NOTIFICACIONES ANTIGUAS
    // =========================================================================

    public function limpiarNotificaciones(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dias' => 'required|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fechaLimite = now()->subDays($request->dias);
        $eliminadas = Notificacion::where('created_at', '<', $fechaLimite)
                                ->where('status', true)
                                ->update(['status' => false]);

        return redirect()->back()->with('success', "Se eliminaron {$eliminadas} notificaciones antiguas.");
    }
}
