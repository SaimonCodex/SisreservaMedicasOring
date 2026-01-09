<?php

namespace App\Jobs;

use App\Models\Pago;
use App\Models\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionEmail;

class EnviarNotificacionPago implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pago;
    protected $estado;

    public function __construct(Pago $pago, $estado)
    {
        $this->pago = $pago;
        $this->estado = $estado;
    }

    public function handle()
    {
        $factura = $this->pago->facturaPaciente;
        $paciente = $factura->paciente;
        
        if (!$paciente || !$paciente->usuario) {
            return;
        }

        $titulo = '';
        $mensaje = '';

        switch ($this->estado) {
            case 'confirmado':
                $titulo = 'Pago Confirmado';
                $mensaje = "Su pago de {$this->pago->monto_pagado_bs} Bs. ha sido confirmado exitosamente.";
                break;
                
            case 'rechazado':
                $titulo = 'Pago Rechazado';
                $mensaje = "Su pago de {$this->pago->monto_pagado_bs} Bs. ha sido rechazado. Por favor, contacte al administrador.";
                break;
        }

        $notificacion = Notificacion::create([
            'receptor_id' => $paciente->id,
            'receptor_rol' => 'Paciente',
            'tipo' => $this->estado === 'confirmado' ? 'Pago_Aprobado' : 'Pago_Rechazado',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'via' => 'Correo',
            'estado_envio' => 'Pendiente',
            'status' => true
        ]);

        Mail::to($paciente->usuario->correo)->send(new NotificacionEmail($notificacion));
        
        $notificacion->estado_envio = 'Enviado';
        $notificacion->save();
    }
}
