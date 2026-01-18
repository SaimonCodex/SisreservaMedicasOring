<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaPasswordCambiada extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'modulo' => 'Seguridad',
            'titulo' => 'Contraseña Actualizada',
            'mensaje' => 'Tu contraseña ha sido restablecida exitosamente. Si no realizaste este cambio, por favor contacta a soporte.',
            'tipo' => 'success',
            'url' => '#',
            'icon' => 'bi-shield-check'
        ];
    }
}
