<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenImagen extends Model
{
    use HasFactory;

    protected $table = 'orden_imagenes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orden_id',
        'tipo_estudio',
        'region_anatomica',
        'proyecciones',
        'contraste',
        'urgente',
        'indicacion_clinica',
        'resultado',
        'archivo_imagen',
        'fecha_resultado',
        'centro_imagenes',
        'observaciones',
        'status'
    ];

    protected $casts = [
        'contraste' => 'boolean',
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
     * Obtener descripción del estudio
     */
    public function getDescripcionEstudioAttribute()
    {
        $desc = "{$this->tipo_estudio} de {$this->region_anatomica}";
        if ($this->contraste) {
            $desc .= " (con contraste)";
        }
        return $desc;
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
