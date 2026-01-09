<?php

namespace App\Mail;

use App\Models\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $notificacion;

    public function __construct(Notificacion $notificacion)
    {
        $this->notificacion = $notificacion;
    }

    public function build()
    {
        return $this->subject($this->notificacion->titulo)
                    ->view('emails.notificacion-generica')
                    ->with([
                        'titulo' => $this->notificacion->titulo,
                        'mensaje' => $this->notificacion->mensaje,
                        'tipo' => $this->notificacion->tipo
                    ]);
    }
}