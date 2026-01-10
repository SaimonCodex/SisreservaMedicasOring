<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicoConsultorio extends Model
{
    use HasFactory;

    protected $table = 'medico_consultorio';

    protected $fillable = [
        'medico_id',
        'especialidad_id',
        'consultorio_id',
        'dia_semana',
        'turno',
        'horario_inicio',
        'horario_fin',
        'status'
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'consultorio_id');
    }
}
