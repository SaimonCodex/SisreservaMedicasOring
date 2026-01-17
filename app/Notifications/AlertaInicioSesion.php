<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;

class AlertaInicioSesion extends Notification
{
    use Queueable;

    public $ip;
    public $userAgent;
    public $time;

    /**
     * Create a new notification instance.
     */
    public function __construct($ip, $userAgent, $time)
    {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->time = $time;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error() // Red alert for security
                    ->subject('Alerta de Seguridad: Nuevo Inicio de Sesión')
                    ->greeting('Hola, ' . $notifiable->nombre_completo)
                    ->line('Se ha detectado un nuevo inicio de sesión en tu cuenta.')
                    ->line('📅 Fecha y Hora: ' . $this->time)
                    ->line('🌐 Dirección IP: ' . $this->ip)
                    ->line('💻 Dispositivo: ' . $this->userAgent)
                    ->line('Si fuiste tú, puedes ignorar este mensaje.')
                    ->line('Si NO fuiste tú, por favor cambia tu contraseña inmediatamente y contacta al soporte.')
                    ->action('Cambiar Contraseña', route('password.request'));
    }
}
