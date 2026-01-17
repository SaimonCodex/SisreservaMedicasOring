<?php

namespace App\Notifications;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoRechazado extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $pago;
    protected $motivo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pago $pago, $motivo = null)
    {
        $this->pago = $pago;
        $this->motivo = $motivo;
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
        
        $mail = (new MailMessage)
                    ->error()
                    ->subject('⚠️ Pago Rechazado - Sistema Médico')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('Lamentamos informarte que tu pago ha sido rechazado.')
                    ->line('💰 Monto: Bs. ' . number_format($this->pago->monto_pagado_bs, 2))
                    ->line('📝 Referencia: ' . $this->pago->referencia)
                    ->line('📅 Cita: ' . \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') . ' a las ' . substr($cita->hora_inicio, 0, 5));
        
        if ($this->motivo) {
            $mail->line('❌ Motivo del rechazo: ' . $this->motivo);
        }
        
        return $mail->action('Registrar Nuevo Pago', route('paciente.pagos.registrar', $cita->id))
                    ->line('Por favor,  verifica los datos y vuelve a registrar tu pago.');
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
        
        return [
            'titulo' => 'Pago Rechazado',
            'mensaje' => 'Tu pago de Bs. ' . number_format($this->pago->monto_pagado_bs, 2) . ' fue rechazado.' . ($this->motivo ? ' Motivo: ' . $this->motivo : ''),
            'tipo' => 'danger',
            'pago_id' => $this->pago->id,
            'cita_id' => $cita->id,
            'link' => route('paciente.pagos.registrar', $cita->id),
            'monto' => $this->pago->monto_pagado_bs,
            'referencia' => $this->pago->referencia,
            'motivo' => $this->motivo,
            'medico_nombre' => 'Dr. ' . $cita->medico->nombre_completo,
            'fecha_cita' => $cita->fecha_cita,
        ];
    }
}
