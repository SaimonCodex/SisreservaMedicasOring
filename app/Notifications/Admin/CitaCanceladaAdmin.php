<?php

namespace App\Notifications\Admin;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaCanceladaAdmin extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;
    protected $motivo;

    public function __construct(Cita $cita, string $motivo)
    {
        $this->cita = $cita;
        $this->motivo = $motivo;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toMail(object $notifiable): MailMessage
    {
        $paciente = $this->cita->paciente;
        
        return (new MailMessage)
            ->subject('Cita Cancelada - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('Un paciente ha cancelado una cita.')
            ->line('**Detalles de la Cita:**')
            ->line('Paciente: ' . $paciente->primer_nombre . ' ' . $paciente->primer_apellido)
            ->line('Fecha: ' . $this->cita->fecha_cita . ' ' . $this->cita->hora_cita)
            ->line('Médico: Dr. ' . $this->cita->medico->primer_nombre . ' ' . $this->cita->medico->primer_apellido)
            ->line('Consultorio: ' . ($this->cita->consultorio->nombre_centro ?? 'N/A'))
            ->line('**Motivo de Cancelación:**')
            ->line($this->motivo)
            ->action('Ver Detalles', url('/citas/' . $this->cita->id))
            ->line('Gracias por mantener el sistema actualizado.');
    }

    public function toArray(object $notifiable): array
    {
        $paciente = $this->cita->paciente;
        $esPacienteEspecial = $this->cita->paciente_especial_id ? true : false;
        $consultorio = $this->cita->consultorio;
        $medico = $this->cita->medico;
        
        return [
            'titulo' => $esPacienteEspecial ? '⭐ Cita Cancelada (Paciente Especial)' : 'Cita Cancelada',
            'mensaje' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido . ' canceló su cita del ' . $this->cita->fecha_cita,
            'tipo' => 'warning',
            'link' => url('/citas/' . $this->cita->id),
            'cita_id' => $this->cita->id,
            'motivo' => $this->motivo,
            'es_paciente_especial' => $esPacienteEspecial,
            // Información adicional para Root
            'consultorio_nombre' => $consultorio ? $consultorio->nombre : 'N/A',
            'consultorio_id' => $this->cita->consultorio_id,
            'paciente_nombre' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido,
            'medico_nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido,
            'fecha_cita' => $this->cita->fecha_cita
        ];
    }
}
