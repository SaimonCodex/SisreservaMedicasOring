<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaActualizada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;
    protected $mensaje;
    protected $tipo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita, string $mensaje, string $tipo = 'info')
    {
        $this->cita = $cita;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo; // 'info', 'success', 'warning', 'danger'
    }

    /**
     * Get the notification's delivery channels.
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
        $mailMessage = (new MailMessage)
            ->subject('Actualización de Cita - ' . config('app.name'))
            ->greeting('¡Hola ' . $notifiable->nombre . '!')
            ->line($this->mensaje)
            ->line('Detalles de la cita:')
            ->line('Fecha: ' . $this->cita->fecha_cita)
            ->line('Hora: ' . $this->cita->hora_cita)
            ->line('Médico: ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido);

        if ($this->cita->consultorio) {
            $mailMessage->line('Consultorio: ' . $this->cita->consultorio->nombre_centro);
        }

        $mailMessage->action('Ver Mis Citas', url('/paciente/citas'))
            ->line('Gracias por utilizar nuestro sistema de reservas.');

        return $mailMessage;
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
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Cita Actualizada',
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'cita_id' => $this->cita->id,
            'link' => url('/paciente/citas/' . $this->cita->id),
            'medico_nombre' => 'Dr. ' . $this->cita->medico->nombre_completo,
            'fecha_cita' => $this->cita->fecha_cita,
            'hora_inicio' => $this->cita->hora_inicio,
        ];
    }
}
