<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenReferencia extends Model
{
    use HasFactory;

    protected $table = 'orden_referencias';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orden_id',
        'especialidad_destino',
        'medico_referido_id',
        'motivo_referencia',
        'resumen_clinico',
        'prioridad',
        'respuesta',
        'fecha_atencion',
        'recomendaciones_especialista',
        'observaciones',
        'status'
    ];

    protected $casts = [
        'fecha_atencion' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Relación con la orden médica principal
     */
    public function orden()
    {
        return $this->belongsTo(OrdenMedica::class, 'orden_id');
    }

    /**
     * Relación con el médico referido (si se especificó uno)
     */
    public function medicoReferido()
    {
        return $this->belongsTo(Medico::class, 'medico_referido_id');
    }

    /**
     * Verificar si fue atendida
     */
    public function fueAtendida()
    {
        return !empty($this->fecha_atencion);
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoAttribute()
    {
        if ($this->fueAtendida()) {
            return 'Atendida';
        }
        
        switch ($this->prioridad) {
            case 'Urgente':
                return 'Pendiente Urgente';
            case 'Preferente':
                return 'Pendiente Preferente';
            default:
                return 'Pendiente';
        }
    }

    /**
     * Obtener color del badge según prioridad
     */
    public function getColorPrioridadAttribute()
    {
        switch ($this->prioridad) {
            case 'Urgente':
                return 'danger';
            case 'Preferente':
                return 'warning';
            default:
                return 'info';
        }
    }
}
