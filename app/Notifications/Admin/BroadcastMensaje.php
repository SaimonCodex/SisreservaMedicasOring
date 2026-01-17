<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastMensaje extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $titulo;
    protected $mensaje;
    protected $prioridad;
    protected $remitente;

    public function __construct($titulo, $mensaje, $prioridad = 'normal', $remitente = 'Administrador Root')
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->prioridad = $prioridad;
        $this->remitente = $remitente;
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
        $mailMessage = (new MailMessage)
            ->subject($this->titulo . ' - ' . config('app.name'))
            ->greeting('¡Hola Administrador!')
            ->line('**Mensaje del Sistema:**')
            ->line($this->mensaje)
            ->line('Remitente: ' . $this->remitente);

        if ($this->prioridad === 'alta') {
            $mailMessage->line('⚠️ **Este es un mensaje de alta prioridad**');
        }

        return $mailMessage->line('Gracias por tu atención.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => $this->prioridad === 'alta' ? '🔔 ' . $this->titulo : '📢 ' . $this->titulo,
            'mensaje' => $this->mensaje,
            'tipo' => $this->prioridad === 'alta' ? 'danger' : 'info',
            'link' => '#',
            'prioridad' => $this->prioridad,
            'remitente' => $this->remitente,
            'es_broadcast' => true
        ];
    }
}
