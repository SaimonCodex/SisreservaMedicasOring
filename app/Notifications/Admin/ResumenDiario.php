<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResumenDiario extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject('☀️ Resumen Diario - ' . now()->format('d/m/Y') . ' - ' . config('app.name'))
            ->greeting('¡Buenos días, Administrador!')
            ->line('Aquí está tu resumen de actividades para hoy:')
            ->line('📅 **Citas Programadas:** ' . $this->data['citas_hoy'])
            ->line('👤 **Pacientes Nuevos:** ' . $this->data['pacientes_nuevos'])
            ->line('💰 **Pagos Pendientes:** ' . $this->data['pagos_pendientes'])
            ->line('⭐ **Citas con Pacientes Especiales:** ' . $this->data['citas_especiales'])
            ->action('Ir al Dashboard', url('/admin/dashboard'))
            ->line('¡Que tengas un excelente día!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => '☀️ Resumen del Día - ' . now()->format('d/m/Y'),
            'mensaje' => "Hoy: {$this->data['citas_hoy']} citas | {$this->data['pacientes_nuevos']} nuevos pacientes | {$this->data['pagos_pendientes']} pagos por revisar",
            'tipo' => 'info',
            'link' => url('/admin/dashboard'),
            'citas_hoy' => $this->data['citas_hoy'],
            'pacientes_nuevos' => $this->data['pacientes_nuevos'],
            'pagos_pendientes' => $this->data['pagos_pendientes'],
            'citas_especiales' => $this->data['citas_especiales'],
            'es_resumen_diario' => true
        ];
    }
}
