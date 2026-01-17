<?php

namespace App\Notifications\Medico;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaReprogramada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;
    protected $fechaAnterior;
    protected $horaAnterior;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita, $fechaAnterior, $horaAnterior)
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
        $paciente = $this->cita->paciente;
        
        return (new MailMessage)
                    ->subject('🔄 Cita Reprogramada - Sistema Médico')
                    ->greeting('Hola, Dr. ' . $notifiable->nombre_completo)
                    ->line('Una de sus citas médicas ha sido **reprogramada**.')
                    ->line('**👤 Paciente:** ' . $paciente->nombre_completo)
                    ->line('')
                    ->line('**Fecha/Hora Anterior:**')
                    ->line('📅 ' . \Carbon\Carbon::parse($this->fechaAnterior)->format('d/m/Y') . ' a las ' . substr($this->horaAnterior, 0, 5))
                    ->line('')
                    ->line('**Nueva Fecha/Hora:**')
                    ->line('📅 ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' a las ' . substr($this->cita->hora_inicio, 0, 5))
                    ->action('Ver Detalles de Cita', route('citas.show', $this->cita->id))
                    ->line('Por favor, revise su agenda actualizada.');
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
        $paciente = $this->cita->paciente;
        
        return [
            'titulo' => 'Cita Reprogramada',
            'mensaje' => 'Cita con ' . $paciente->nombre_completo . ' movida al ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y'),
            'tipo' => 'info',
            'cita_id' => $this->cita->id,
            'link' => route('citas.show', $this->cita->id),
            'paciente_nombre' => $paciente->nombre_completo,
            'fecha_anterior' => $this->fechaAnterior,
            'hora_anterior' => substr($this->horaAnterior, 0, 5),
            'fecha_nueva' => $this->cita->fecha_cita,
            'hora_nueva' => substr($this->cita->hora_inicio, 0, 5),
        ];
    }
}
