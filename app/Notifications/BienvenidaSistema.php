<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BienvenidaSistema extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $usuario;

    /**
     * Create a new notification instance.
     */
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
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
                    ->subject('¡Bienvenido a SisReservaMedicas!')
                    ->greeting('Hola, ' . $this->usuario->nombre_completo)
                    ->line('Estamos encantados de tenerte con nosotros.')
                    ->line('Tu cuenta ha sido creada exitosamente. Ahora puedes acceder a nuestro portal para gestionar tus citas médicas, ver tu historial y más.')
                    ->action('Ir al Portal', route('login'))
                    ->line('Si tienes alguna duda, no dudes en contactarnos.')
                    ->salutation('Saludos, El equipo de SisReservaMedicas');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $link = route('home');
        if ($notifiable instanceof \App\Models\Paciente) {
            $link = route('paciente.dashboard');
        } elseif ($notifiable instanceof \App\Models\Medico) {
            $link = route('medico.dashboard');
        }

        return [
            'titulo' => '¡Bienvenido al Sistema!',
            'mensaje' => 'Gracias por registrarte. Explora tu portal y comienza a gestionar tus citas.',
            'tipo' => 'success',
            'link' => $link,
            'icon' => 'bi-person-check-fill',
            'color' => 'emerald'
        ];
    }
}
