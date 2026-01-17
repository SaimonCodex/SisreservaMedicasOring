<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaHistoriaBase extends Model
{
    use HasFactory;

    protected $table = 'auditorias_historia_base';
    
    protected $fillable = [
        'historia_clinica_base_id',
        'medico_id',
        'tipo_accion',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'motivo_cambio',
        'ip_address',
        'user_agent'
    ];

    /**
     * Relación con la historia clínica base
     */
    public function historiaClinicaBase()
    {
        return $this->belongsTo(HistoriaClinicaBase::class, 'historia_clinica_base_id');
    }

    /**
     * Relación con el médico que realizó el cambio
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
