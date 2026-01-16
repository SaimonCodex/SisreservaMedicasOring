<?php

namespace App\Notifications\Medico;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaCanceladaPaciente extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;
    protected $motivo;

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
        $paciente = $this->cita->paciente;
        
        $mail = (new MailMessage)
                    ->error()
                    ->subject('⚠️ Cita Cancelada - Sistema Médico')
                    ->greeting('Hola, Dr. ' . $notifiable->nombre_completo)
                    ->line('Le informamos que una de sus citas médicas ha sido **CANCELADA**.')
                    ->line('**Detalles de la cita cancelada:**')
                    ->line('👤 Paciente: ' . $paciente->nombre_completo)
                    ->line('📅 Fecha: ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y'))
                    ->line('🕐 Hora: ' . substr($this->cita->hora_inicio, 0, 5));

        if ($this->motivo) {
            $mail->line('📝 Motivo: ' . $this->motivo);
        }

        return $mail->action('Ver Mi Agenda', route('medico.dashboard'))
                    ->line('Su horario ha sido actualizado automáticamente.');
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
            'titulo' => 'Cita Cancelada',
            'mensaje' => 'La cita con ' . $paciente->nombre_completo . ' del ' . \Carbon\Carbon::parse($this->cita->fecha_cita)->format('d/m/Y') . ' ha sido cancelada.',
            'tipo' => 'warning',
            'cita_id' => $this->cita->id,
            'link' => route('medico.dashboard'),
            'paciente_nombre' => $paciente->nombre_completo,
            'fecha_cita' => $this->cita->fecha_cita,
            'hora_inicio' => substr($this->cita->hora_inicio, 0, 5),
            'motivo' => $this->motivo,
        ];
    }
}
