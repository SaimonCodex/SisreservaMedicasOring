<?php

namespace App\Notifications\Admin;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaPagoPendiente extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $pagos;
    protected $totalPendiente;

    public function __construct($pagos)
    {
        $this->pagos = $pagos;
        $this->totalPendiente = $pagos->sum('monto_pagado_bs');
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
        $count = $this->pagos->count();
        
        return (new MailMessage)
            ->subject('⏰ Recordatorio: Pagos Pendientes de Revisión - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line("Tienes **{$count} pago(s)** que llevan más de 24 horas esperando revisión.")
            ->line('**Monto total pendiente:** Bs. ' . number_format($this->totalPendiente, 2))
            ->line('Es importante revisar y aprobar/rechazar estos pagos lo antes posible para mantener la operación fluida.')
            ->action('Revisar Pagos', url('/pagos'))
            ->line('Gracias por tu atención.');
    }

    public function toArray(object $notifiable): array
    {
        $count = $this->pagos->count();
        
        return [
            'titulo' => '⏰ Recordatorio: Pagos Pendientes',
            'mensaje' => "Tienes {$count} pago(s) pendientes de revisión por más de 24 horas (Bs. " . number_format($this->totalPendiente, 2) . ")",
            'tipo' => 'warning',
            'link' => url('/pagos'),
            'pagos_count' => $count,
            'monto_total' => $this->totalPendiente,
            'es_alerta_automatica' => true
        ];
    }
}
