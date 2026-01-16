<?php

namespace App\Notifications\Medico;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoConfirmadoCita extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $pago;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $cita = $this->pago->facturaPaciente->cita;
        $paciente = $cita->paciente;
        
        return (new MailMessage)
                    ->subject('💰 Pago Confirmado - Sistema Médico')
                    ->greeting('¡Hola, Dr. ' . $notifiable->nombre_completo . '!')
                    ->line('Se ha confirmado el pago de una de sus citas.')
                    ->line('**Detalles del pago:**')
                    ->line('👤 Paciente: ' . $paciente->nombre_completo)
                    ->line('💵 Monto: Bs. ' . number_format($this->pago->monto_pagado_bs, 2) . ' (USD $' . number_format($this->pago->monto_equivalente_usd, 2) . ')')
                    ->line('📝 Referencia: ' . $this->pago->referencia)
                    ->line('📅 Cita: ' . \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') . ' a las ' . substr($cita->hora_inicio, 0, 5))
                    ->action('Ver Detalles', route('citas.show', $cita->id))
                    ->line('El pago ha sido procesado exitosamente.');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $cita = $this->pago->facturaPaciente->cita;
        $paciente = $cita->paciente;
        
        return [
            'titulo' => 'Pago Confirmado',
            'mensaje' => 'Pago de Bs. ' . number_format($this->pago->monto_pagado_bs, 2) . ' confirmado para cita con ' . $paciente->nombre_completo,
            'tipo' => 'success',
            'pago_id' => $this->pago->id,
            'cita_id' => $cita->id,
            'link' => route('citas.show', $cita->id),
            'paciente_nombre' => $paciente->nombre_completo,
            'monto_bs' => $this->pago->monto_pagado_bs,
            'monto_usd' => $this->pago->monto_equivalente_usd,
            'referencia' => $this->pago->referencia,
            'fecha_cita' => $cita->fecha_cita,
        ];
    }
}
