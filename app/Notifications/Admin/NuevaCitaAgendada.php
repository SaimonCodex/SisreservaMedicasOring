<?php

namespace App\Notifications\Admin;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaCitaAgendada extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
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
            ->subject('Nueva Cita Agendada - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('Se ha agendado una nueva cita en el sistema.')
            ->line('**Detalles de la Cita:**')
            ->line('Paciente: ' . $paciente->primer_nombre . ' ' . $paciente->primer_apellido)
            ->line('Fecha: ' . $this->cita->fecha_cita . ' ' . $this->cita->hora_cita)
            ->line('Médico: Dr. ' . $this->cita->medico->primer_nombre . ' ' . $this->cita->medico->primer_apellido)
            ->line('Consultorio: ' . ($this->cita->consultorio->nombre_centro ?? 'N/A'))
            ->action('Ver Cita', url('/citas/' . $this->cita->id))
            ->line('Gracias por estar atento a las nuevas citas.');
    }

    public function toArray(object $notifiable): array
    {
        $paciente = $this->cita->paciente;
        $esPacienteEspecial = $this->cita->paciente_especial_id ? true : false;
        $consultorio = $this->cita->consultorio;
        $medico = $this->cita->medico;
        
        return [
            'titulo' => $esPacienteEspecial ? '⭐ Nueva Cita (Paciente Especial)' : 'Nueva Cita Agendada',
            'mensaje' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido . ' agendó una cita para el ' . $this->cita->fecha_cita,
            'tipo' => $esPacienteEspecial ? 'warning' : 'success',
            'link' => url('/citas/' . $this->cita->id),
            'cita_id' => $this->cita->id,
            'es_paciente_especial' => $esPacienteEspecial,
            // Información adicional para Root
            'consultorio_nombre' => $consultorio ? $consultorio->nombre : 'N/A',
            'consultorio_id' => $this->cita->consultorio_id,
            'paciente_nombre' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido,
            'paciente_documento' => $paciente->tipo_documento . '-' . $paciente->numero_documento,
            'medico_nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido,
            'fecha_cita' => $this->cita->fecha_cita,
            'hora_inicio' => $this->cita->hora_inicio
        ];
    }
}
