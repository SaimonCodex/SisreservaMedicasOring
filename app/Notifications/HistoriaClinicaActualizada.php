<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EvolucionClinica;

class HistoriaClinicaActualizada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $evolucion;

    /**
     * Create a new notification instance.
     */
    public function __construct(EvolucionClinica $evolucion)
    {
        $this->evolucion = $evolucion;
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
                    ->subject('Historia Clínica Actualizada - Sistema Médico')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('El Dr. ' . $this->evolucion->medico->nombre_completo . ' ha actualizado tu historia clínica.')
                    ->line('Se ha registrado una nueva evolución clínica correspondiente a la cita del ' . \Carbon\Carbon::parse($this->evolucion->cita->fecha_cita)->format('d/m/Y') . '.')
                    ->action('Ver Historia Clínica', route('historia-clinica.evoluciones.show', $this->evolucion->cita_id))
                    ->line('Ingresa al portal para ver los detalles completos.');
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
            'titulo' => 'Historia Clínica Actualizada',
            'mensaje' => 'El Dr. ' . $this->evolucion->medico->primer_apellido . ' agregó una nueva evolución.',
            'tipo' => 'success',
            'evolucion_id' => $this->evolucion->id,
            'cita_id' => $this->evolucion->cita_id,
            'link' => route('historia-clinica.evoluciones.show', $this->evolucion->cita_id),
            'medico_nombre' => 'Dr. ' . $this->evolucion->medico->nombre_completo,
            'fecha' => $this->evolucion->created_at->format('d/m/Y'),
        ];
    }
}
