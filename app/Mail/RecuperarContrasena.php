<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;

    public function __construct(Usuario $usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Recuperación de Contraseña - Sistema de Reservas Médicas')
                    ->view('emails.recuperar-contrasena')
                    ->with([
                        'nombre' => $this->obtenerNombreUsuario(),
                        'token' => $this->token
                    ]);
    }

    private function obtenerNombreUsuario()
    {
        if ($this->usuario->paciente) {
            return $this->usuario->paciente->primer_nombre;
        } elseif ($this->usuario->medico) {
            return $this->usuario->medico->primer_nombre;
        } elseif ($this->usuario->administrador) {
            return $this->usuario->administrador->primer_nombre;
        }
        
        return 'Usuario';
    }
}
