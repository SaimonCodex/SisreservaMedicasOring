<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Cita;

class CitaReprogramada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $cita;
    public $fechaAnterior;
    public $horaAnterior;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita, $fechaAnterior = null, $horaAnterior = null)
    {
        $this->cita = $cita;
        $this->fechaAnterior = $fechaAnterior;
        $this->horaAnterior = $horaAnterior;
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
        $mensaje = (new MailMessage)
                    ->subject('Actualización de Cita - Sistema Médico')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('Se han realizado cambios en tu cita médica.');

        if ($this->fechaAnterior && $this->horaAnterior) {
            $mensaje->line('🕒 Fecha anterior: ' . $this->fechaAnterior . ' ' . $this->horaAnterior);
        }
                    
        return $mensaje->line('✅ NUEVA FECHA: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' a las ' . substr($this->cita->hora_inicio, 0, 5))
                    ->line('👨‍⚕️ Médico: Dr. ' . $this->cita->medico->nombre_completo)
                    ->line('📍 Consultorio: ' . $this->cita->consultorio->nombre)
                    ->action('Ver Detalles', route('paciente.citas.show', $this->cita->id))
                    ->line('Si no estás de acuerdo con este cambio, por favor contáctanos.');
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
            'titulo' => 'Cita Reprogramada',
            'mensaje' => 'Nueva fecha: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' - ' . substr($this->cita->hora_inicio, 0, 5),
            'tipo' => 'warning',
            'cita_id' => $this->cita->id,
            'link' => route('paciente.citas.show', $this->cita->id),
            'medico_nombre' => 'Dr. ' . $this->cita->medico->nombre_completo,
            'fecha_anterior' => $this->fechaAnterior,
            'fecha_nueva' => $this->cita->fecha_cita,
            'hora_nueva' => $this->cita->hora_inicio,
        ];
    }
}
