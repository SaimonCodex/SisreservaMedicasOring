<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenExamen extends Model
{
    use HasFactory;

    protected $table = 'orden_examenes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orden_id',
        'tipo_examen',
        'nombre_examen',
        'urgente',
        'indicacion_clinica',
        'resultado',
        'fecha_resultado',
        'laboratorio',
        'observaciones',
        'status'
    ];

    protected $casts = [
        'urgente' => 'boolean',
        'fecha_resultado' => 'date',
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
     * Verificar si tiene resultado registrado
     */
    public function tieneResultado()
    {
        return !empty($this->resultado);
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoAttribute()
    {
        if ($this->tieneResultado()) {
            return 'Procesado';
        }
        return $this->urgente ? 'Pendiente Urgente' : 'Pendiente';
    }
}
