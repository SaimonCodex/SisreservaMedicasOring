<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudOrden extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_orden';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orden_id',
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

    protected $casts = [
        'token_expira_at' => 'datetime',
        'acceso_valido_hasta' => 'datetime',
        'intentos_fallidos' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Relación con la orden médica
     */
    public function orden()
    {
        return $this->belongsTo(OrdenMedica::class, 'orden_id');
    }

    /**
     * Relación con el paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relación con el médico que solicita acceso
     */
    public function medicoSolicitante()
    {
        return $this->belongsTo(Medico::class, 'medico_solicitante_id');
    }

    /**
     * Relación con el médico propietario de la orden
     */
    public function medicoPropietario()
    {
        return $this->belongsTo(Medico::class, 'medico_propietario_id');
    }

    /**
     * Verificar si un médico tiene acceso activo a una orden específica
     */
    public static function tieneAccesoActivo($medicoId, $ordenId)
    {
        return self::where('medico_solicitante_id', $medicoId)
                   ->where('orden_id', $ordenId)
                   ->where('estado_permiso', 'Aprobado')
                   ->where('acceso_valido_hasta', '>', now())
                   ->where('status', true)
                   ->exists();
    }

    /**
     * Obtener todas las órdenes a las que un médico tiene acceso aprobado para un paciente
     */
    public static function obtenerOrdenesConAcceso($medicoId, $pacienteId)
    {
        return self::where('medico_solicitante_id', $medicoId)
                   ->where('paciente_id', $pacienteId)
                   ->where('estado_permiso', 'Aprobado')
                   ->where('acceso_valido_hasta', '>', now())
                   ->where('status', true)
                   ->pluck('orden_id');
    }

    /**
     * Obtener solicitudes pendientes de un paciente
     */
    public static function solicitudesPendientes($pacienteId)
    {
        return self::where('paciente_id', $pacienteId)
                   ->where('estado_permiso', 'Pendiente')
                   ->where('status', true)
                   ->with(['orden', 'medicoSolicitante', 'medicoPropietario'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Verificar si ya existe una solicitud pendiente o activa entre médico y orden
     */
    public static function existeSolicitud($medicoId, $ordenId)
    {
        return self::where('medico_solicitante_id', $medicoId)
                   ->where('orden_id', $ordenId)
                   ->where('status', true)
                   ->whereIn('estado_permiso', ['Pendiente', 'Aprobado'])
                   ->exists();
    }

    /**
     * Obtener color del badge según estado
     */
    public function getColorEstadoAttribute()
    {
        switch ($this->estado_permiso) {
            case 'Aprobado':
                return 'success';
            case 'Rechazado':
                return 'danger';
            case 'Expirado':
                return 'secondary';
            default:
                return 'warning';
        }
    }
}
