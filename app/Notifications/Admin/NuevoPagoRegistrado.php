<?php

namespace App\Notifications\Admin;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoPagoRegistrado extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $cita = $this->pago->facturaPaciente->cita;
        $paciente = $cita->paciente;
        
        return (new MailMessage)
            ->subject('Nuevo Pago Registrado - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('Se ha registrado un nuevo pago en el sistema.')
            ->line('**Detalles del Pago:**')
            ->line('Paciente: ' . $paciente->primer_nombre . ' ' . $paciente->primer_apellido)
            ->line('Monto: Bs. ' . number_format($this->pago->monto_pagado_bs, 2))
            ->line('Referencia: ' . $this->pago->referencia)
            ->line('Método: ' . $this->pago->metodoPago->nombre)
            ->line('Fecha cita: ' . $cita->fecha_cita . ' ' . $cita->hora_cita)
            ->action('Ver Pago', url('/pagos/' . $this->pago->id))
            ->line('Por favor, revise y confirme o rechace el pago lo antes posible.');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toArray(object $notifiable): array
    {
        $cita = $this->pago->facturaPaciente->cita;
        $paciente = $cita->paciente;
        $esPacienteEspecial = $cita->paciente_especial_id ? true : false;
        $consultorio = $cita->consultorio;
        $medico = $cita->medico;
        
        return [
            'titulo' => $esPacienteEspecial ? '⭐ Nuevo Pago (Paciente Especial)' : 'Nuevo Pago Registrado',
            'mensaje' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido . ' registró un pago de Bs. ' . number_format($this->pago->monto_pagado_bs, 2),
            'tipo' => 'info',
            'link' => url('/pagos/' . $this->pago->id),
            'pago_id' => $this->pago->id,
            'cita_id' => $cita->id,
            'monto' => $this->pago->monto_pagado_bs,
            'referencia' => $this->pago->referencia,
            // Información adicional para Root
            'consultorio_nombre' => $consultorio ? $consultorio->nombre : 'N/A',
            'consultorio_id' => $cita->consultorio_id,
            'paciente_nombre' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido,
            'paciente_documento' => $paciente->tipo_documento . '-' . $paciente->numero_documento,
            'medico_nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido,
            'fecha_cita' => $cita->fecha_cita,
            'acciones' => [
                [
                    'texto' => 'Ver Comprobante',
                    'icono' => 'file-earmark-text',
                    'tipo' => 'secondary',
                    'url' => url('/pagos/' . $this->pago->id)
                ],
                [
                    'texto' => 'Confirmar',
                    'icono' => 'check-circle',
                    'tipo' => 'success',
                    'accion' => 'confirmar-pago',
                    'data' => ['pago_id' => $this->pago->id]
                ]
            ]
        ];
    }
}
