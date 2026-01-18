<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaCuentaBloqueada extends Notification
{
    use Queueable;

    protected $blockedUntil;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($blockedUntil, $reason = 'Múltiples intentos fallidos de recuperación de contraseña')
    {
        $this->blockedUntil = $blockedUntil;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Cuenta Bloqueada Temporalmente - Sistema Médico')
            ->view('emails.cuenta-bloqueada', [
                'usuario' => $notifiable,
                'blockedUntil' => $this->blockedUntil,
                'reason' => $this->reason
            ]);
    }

    /**
     * Get the array representation of the notification (for database/broadcast).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'modulo' => 'Seguridad',
            'titulo' => 'Cuenta Bloqueada Temporalmente',
            'mensaje' => "Tu cuenta ha sido bloqueada hasta {$this->blockedUntil->format('d/m/Y H:i')} por seguridad. Motivo: {$this->reason}",
            'tipo' => 'warning',
            'url' => route('login'),
            'icon' => 'bi-lock-fill',
            'blocked_until' => $this->blockedUntil->toDateTimeString(),
            'reason' => $this->reason
        ];
    }
}
