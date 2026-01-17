<?php

namespace App\Notifications\Medico;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaCitaAsignada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;

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
        $paciente = $this->cita->paciente;
        $especialidad = $this->cita->especialidad;
        
        return (new MailMessage)
                    ->subject('📅 Nueva Cita Asignada - Sistema Médico')
                    ->greeting('¡Hola, Dr. ' . $notifiable->nombre_completo . '!')
                    ->line('Se le ha asignado una nueva cita médica.')
                    ->line('**Detalles de la cita:**')
                    ->line('👤 Paciente: ' . $paciente->nombre_completo)
                    ->line('📅 Fecha: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y'))
                    ->line('🕐 Hora: ' . substr($this->cita->hora_inicio, 0, 5) . ' - ' . substr($this->cita->hora_fin, 0, 5))
                    ->line('🏥 Especialidad: ' . $especialidad->nombre)
                    ->line('📍 Consultorio: ' . ($this->cita->consultorio->nombre ?? 'Por definir'))
                    ->action('Ver Detalles de Cita', route('citas.show', $this->cita->id))
                    ->line('Por favor, revise los detalles en su portal médico.');
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
        $especialidad = $this->cita->especialidad;
        
        return [
            'titulo' => 'Nueva Cita Asignada',
            'mensaje' => 'Cita con ' . $paciente->nombre_completo . ' el ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' a las ' . substr($this->cita->hora_inicio, 0, 5),
            'tipo' => 'info',
            'cita_id' => $this->cita->id,
            'link' => route('citas.show', $this->cita->id),
            'paciente_nombre' => $paciente->nombre_completo,
            'fecha_cita' => $this->cita->fecha_cita,
            'hora_inicio' => substr($this->cita->hora_inicio, 0, 5),
            'hora_fin' => substr($this->cita->hora_fin, 0, 5),
            'especialidad' => $especialidad->nombre,
            'consultorio' => $this->cita->consultorio->nombre ?? 'Por definir',
        ];
    }
}
