<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SolicitudHistorial;

class SolicitudAccesoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $esParaRepresentante;
    public $nombrePaciente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SolicitudHistorial $solicitud, $esParaRepresentante = false, $nombrePaciente = '')
    {
        $this->solicitud = $solicitud;
        $this->esParaRepresentante = $esParaRepresentante;
        $this->nombrePaciente = $nombrePaciente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $medicoSolicitante = $this->solicitud->medicoSolicitante->usuario->primer_nombre . ' ' . $this->solicitud->medicoSolicitante->usuario->primer_apellido;
        
        $subject = 'Solicitud de Acceso a Historial Médico';
        if ($this->esParaRepresentante) {
            $subject .= ' de su Representado';
        }

        return $this->subject($subject)
                    ->view('emails.solicitud-acceso');
    }
}
