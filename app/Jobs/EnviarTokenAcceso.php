<?php

namespace App\Jobs;

use App\Models\SolicitudHistorial;
use App\Models\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionEmail;

class EnviarTokenAcceso implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $solicitud;

    public function __construct(SolicitudHistorial $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function handle()
    {
        $medicoSolicitante = $this->solicitud->medicoSolicitante;
        
        if (!$medicoSolicitante || !$medicoSolicitante->usuario) {
            return;
        }

        $titulo = 'Solicitud de Acceso a Historial Médico';
        $mensaje = "Se ha solicitado acceso al historial médico de un paciente. Use el siguiente token para autorizar: {$this->solicitud->token_validacion}";

        $notificacion = Notificacion::create([
            'receptor_id' => $medicoSolicitante->id,
            'receptor_rol' => 'Medico',
            'tipo' => 'Alerta_Adm',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'via' => 'Correo',
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        Mail::to($medicoSolicitante->usuario->correo)->send(new NotificacionEmail($notificacion));
        
        $notificacion->estado_envio = 'Enviado';
        $notificacion->save();
    }
}
