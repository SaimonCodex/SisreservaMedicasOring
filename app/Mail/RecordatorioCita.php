<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioCita extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        return $this->subject('Recordatorio de Cita Médica')
                    ->view('emails.recordatorio-cita')
                    ->with([
                        'paciente' => $this->cita->paciente,
                        'medico' => $this->cita->medico,
                        'especialidad' => $this->cita->especialidad,
                        'fecha' => $this->cita->fecha_cita,
                        'hora' => $this->cita->hora_inicio,
                        'consultorio' => $this->cita->consultorio
                    ]);
    }
}
