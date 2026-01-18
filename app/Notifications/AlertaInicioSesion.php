<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaInicioSesion extends Notification
{
    use Queueable;

    public $ip;
    public $userAgent;
    public $time;

    public function __construct($ip, $userAgent, $time)
    {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->time = $time;
    }

    public function via(object $notifiable): array
    {
        // Enviar por email, guardar en DB y transmitir en tiempo real
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
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

    public function toArray(object $notifiable): array
    {
        return [
            'modulo' => 'Seguridad',
            'titulo' => 'Nuevo Inicio de Sesión',
            'mensaje' => "Se detectó acceso desde la IP: {$this->ip} el {$this->time}.",
            'tipo' => 'info',
            'url' => '#',
            'icon' => 'bi-shield-exclamation'
        ];
    }
}
