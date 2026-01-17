<?php

namespace App\Notifications\Admin;

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

    public function __construct(Cita $cita, $fechaAnterior, $horaAnterior)
    {
        $this->cita = $cita;
        $this->fechaAnterior = $fechaAnterior;
        $this->horaAnterior = $horaAnterior;
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
            ->subject('Cita Reprogramada - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('Una cita ha sido reprogramada.')
            ->line('**Detalles:**')
            ->line('Paciente: ' . $paciente->primer_nombre . ' ' . $paciente->primer_apellido)
            ->line('Fecha anterior: ' . $this->fechaAnterior . ' ' . $this->horaAnterior)
            ->line('Nueva fecha: ' . $this->cita->fecha_cita . ' ' . $this->cita->hora_cita)
            ->line('Médico: Dr. ' . $this->cita->medico->primer_nombre . ' ' . $this->cita->medico->primer_apellido)
            ->action('Ver Cita', url('/citas/' . $this->cita->id))
            ->line('Verifique la disponibilidad del médico.');
    }

    public function toArray(object $notifiable): array
    {
        $paciente = $this->cita->paciente;
        $esPacienteEspecial = $this->cita->paciente_especial_id ? true : false;
        $consultorio = $this->cita->consultorio;
        $medico = $this->cita->medico;
        
        return [
            'titulo' => $esPacienteEspecial ? '⭐ Cita Reprogramada (Paciente Especial)' : 'Cita Reprogramada',
            'mensaje' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido . ' reprogramó su cita del ' . $this->fechaAnterior . ' al ' . $this->cita->fecha_cita,
            'tipo' => 'warning',
            'link' => url('/citas/' . $this->cita->id),
            'cita_id' => $this->cita->id,
            'es_paciente_especial' => $esPacienteEspecial,
            // Información adicional para Root
            'consultorio_nombre' => $consultorio ? $consultorio->nombre : 'N/A',
            'consultorio_id' => $this->cita->consultorio_id,
            'paciente_nombre' => $paciente->primer_nombre . ' ' . $paciente->primer_apellido,
            'medico_nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido,
            'fecha_anterior' => $this->fechaAnterior,
            'fecha_nueva' => $this->cita->fecha_cita
        ];
    }
}
