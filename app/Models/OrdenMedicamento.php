<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMedicamento extends Model
{
    use HasFactory;

    protected $table = 'orden_medicamentos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orden_id',
        'medicamento',
        'presentacion',
        'cantidad',
        'dosis',
        'via_administracion',
        'duracion_dias',
        'indicaciones',
        'status'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'duracion_dias' => 'integer',
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
     * Obtener descripción formateada del medicamento
     */
    public function getDescripcionCompletaAttribute()
    {
        $desc = $this->medicamento;
        if ($this->presentacion) {
            $desc .= " ({$this->presentacion})";
        }
        if ($this->dosis) {
            $desc .= " - {$this->dosis}";
        }
        if ($this->duracion_dias) {
            $desc .= " por {$this->duracion_dias} días";
        }
        return $desc;
    }
}
