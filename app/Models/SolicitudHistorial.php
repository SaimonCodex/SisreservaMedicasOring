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
        'evolucion_id',
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

    protected $casts = [
        'token_expira_at' => 'datetime',
        'acceso_valido_hasta' => 'datetime',
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

    public function evolucion()
    {
        return $this->belongsTo(EvolucionClinica::class, 'evolucion_id');
    }

    /**
     * Verificar si el médico tiene acceso activo a una evolución
     */
    public static function tieneAccesoActivo($medicoId, $evolucionId)
    {
        return self::where('medico_solicitante_id', $medicoId)
                   ->where('evolucion_id', $evolucionId)
                   ->where('estado_permiso', 'Aprobado')
                   ->where('acceso_valido_hasta', '>', now())
                   ->where('status', true)
                   ->exists();
    }

    /**
     * Obtener solicitudes aprobadas para un médico y paciente
     */
    public static function obtenerAccesosActivos($medicoId, $pacienteId)
    {
        return self::where('medico_solicitante_id', $medicoId)
                   ->where('paciente_id', $pacienteId)
                   ->where('estado_permiso', 'Aprobado')
                   ->where('acceso_valido_hasta', '>', now())
                   ->where('status', true)
                   ->pluck('evolucion_id');
    }
}
