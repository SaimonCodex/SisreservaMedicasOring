<?php

namespace App\Jobs;

use App\Models\Cita;
use App\Models\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CitaConfirmada;
use App\Mail\RecordatorioCita;

class EnviarNotificacionCita implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cita;
    protected $tipo;

    public function __construct(Cita $cita, $tipo)
    {
        $this->cita = $cita;
        $this->tipo = $tipo;
    }

    public function handle()
    {
        $paciente = $this->cita->paciente;
        
        if (!$paciente || !$paciente->usuario) {
            return;
        }

        $titulo = '';
        $mensaje = '';
        $via = 'Correo';

        switch ($this->tipo) {
            case 'confirmacion':
                $titulo = 'Cita Confirmada';
                $mensaje = "Su cita con el Dr. {$this->cita->medico->primer_apellido} para el {$this->cita->fecha_cita} a las {$this->cita->hora_inicio} ha sido confirmada.";
                Mail::to($paciente->usuario->correo)->send(new CitaConfirmada($this->cita));
                break;
                
            case 'recordatorio':
                $titulo = 'Recordatorio de Cita';
                $mensaje = "Recuerde su cita médica mañana {$this->cita->fecha_cita} a las {$this->cita->hora_inicio} con el Dr. {$this->cita->medico->primer_apellido}.";
                Mail::to($paciente->usuario->correo)->send(new RecordatorioCita($this->cita));
                break;
                
            case 'cancelacion':
                $titulo = 'Cita Cancelada';
                $mensaje = "Su cita programada para el {$this->cita->fecha_cita} ha sido cancelada.";
                break;
        }

        // Registrar notificación en base de datos
        Notificacion::create([
            'receptor_id' => $paciente->id,
            'receptor_rol' => 'Paciente',
            'tipo' => $this->tipo === 'recordatorio' ? 'Recordatorio_Cita' : 'Cancelacion',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'via' => $via,
            'estado_envio' => 'Enviado',
            'status' => true
        ]);
    }
}