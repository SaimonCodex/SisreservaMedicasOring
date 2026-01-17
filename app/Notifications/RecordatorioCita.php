<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Cita;

class RecordatorioCita extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $cita;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
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
        return (new MailMessage)
                    ->subject('Recordatorio de Cita Médica - Mañana')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('Te recordamos que tienes una cita médica programada para mañana.')
                    ->line('📅 Fecha: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y'))
                    ->line('🕒 Hora: ' . substr($this->cita->hora_inicio, 0, 5))
                    ->line('👨‍⚕️ Médico: Dr. ' . $this->cita->medico->nombre_completo)
                    ->line('📍 Consultorio: ' . $this->cita->consultorio->nombre)
                    ->action('Ver Detalles', route('paciente.citas.show', $this->cita->id))
                    ->line('Por favor llega 10 minutos antes de tu hora programada.');
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
            'titulo' => 'Recordatorio de Cita',
            'mensaje' => 'Mañana tienes cita con el Dr. ' . $this->cita->medico->primer_apellido . ' a las ' . substr($this->cita->hora_inicio, 0, 5),
            'tipo' => 'info',
            'cita_id' => $this->cita->id,
            'link' => route('paciente.citas.show', $this->cita->id),
            'fecha_cita' => $this->cita->fecha_cita,
            'medico_nombre' => 'Dr. ' . $this->cita->medico->nombre_completo,
            'hora_cita' => substr($this->cita->hora_inicio, 0, 5),
        ];
    }
}
