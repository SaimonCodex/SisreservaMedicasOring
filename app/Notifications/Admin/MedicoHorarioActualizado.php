<?php

namespace App\Notifications\Admin;

use App\Models\Medico;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicoHorarioActualizado extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $medico;

    public function __construct(Medico $medico)
    {
        $this->medico = $medico;
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
        return (new MailMessage)
            ->subject('Horario Médico Actualizado - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('Un médico ha actualizado su horario de atención.')
            ->line('**Detalles:**')
            ->line('Médico: Dr. ' . $this->medico->primer_nombre . ' ' . $this->medico->primer_apellido)
            ->action('Ver Horarios', url('/medicos/' . $this->medico->id . '/horarios'))
            ->line('Revise los nuevos horarios para verificar disponibilidad.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Horario Actualizado',
            'mensaje' => 'Dr. ' . $this->medico->primer_nombre . ' ' . $this->medico->primer_apellido . ' actualizó sus horarios de atención',
            'tipo' => 'info',
            'link' => url('/medicos/' . $this->medico->id . '/horarios'),
            'medico_id' => $this->medico->id
        ];
    }
}
