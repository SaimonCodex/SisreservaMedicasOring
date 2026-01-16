<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Cita;

class CitaCancelada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $cita;
    public $motivo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita, $motivo = null)
    {
        $this->cita = $cita;
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
        $mail = (new MailMessage)
                    ->error() // Use error layout for red color/urgency
                    ->subject('AVISO IMPORTANTE: Cita Cancelada - Sistema Médico')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('Te informamos que tu cita médica ha sido CANCELADA.')
                    ->line('Detalles de la cita cancelada:')
                    ->line('📅 Fecha original: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y'))
                    ->line('👨‍⚕️ Médico: Dr. ' . $this->cita->medico->nombre_completo);

        if ($this->motivo) {
            $mail->line('📝 Motivo: ' . $this->motivo);
        }

        return $mail->action('Reagendar Cita', route('paciente.citas.create'))
                    ->line('Lamentamos los inconvenientes. Por favor ingresa al portal para solicitar una nueva cita.');
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
        return [
            'titulo' => 'Cita Cancelada',
            'mensaje' => 'Tu cita para el ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' ha sido cancelada.',
            'tipo' => 'danger',
            'cita_id' => $this->cita->id,
            'link' => route('paciente.citas.create'),
            'motivo' => $this->motivo,
            'fecha_cita' => $this->cita->fecha_cita,
            'medico_nombre' => 'Dr. ' . $this->cita->medico->nombre_completo,
        ];
    }
}
