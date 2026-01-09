<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistroExitoso extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Registro Exitoso - Sistema de Reservas Médicas')
                    ->view('emails.registro-exitoso')
                    ->with([
                        'nombre' => $this->usuario->paciente->primer_nombre ?? 
                                   $this->usuario->medico->primer_nombre ?? 
                                   $this->usuario->administrador->primer_nombre ?? 'Usuario'
                    ]);
    }
}