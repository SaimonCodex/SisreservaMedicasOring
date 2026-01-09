<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudHistorial extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_historial';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'medico_solicitante_id',
        'medico_propietario_id',
        'token_validacion',
        'token_expira_at',
        'intentos_fallidos',
        'motivo_solicitud',
        'estado_permiso',
        'acceso_valido_hasta',
        'observaciones',
        'status'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medicoSolicitante()
    {
        return $this->belongsTo(Medico::class, 'medico_solicitante_id');
    }

    public function medicoPropietario()
    {
        return $this->belongsTo(Medico::class, 'medico_propietario_id');
    }
}
