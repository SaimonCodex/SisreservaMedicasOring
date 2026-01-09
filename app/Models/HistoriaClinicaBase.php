<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinicaBase extends Model
{
    use HasFactory;

    protected $table = 'historia_clinica_base';
    protected $primaryKey = 'id';
    protected $fillable = [
        'paciente_id',
        'tipo_sangre',
        'alergias',
        'alergias_medicamentos',
        'antecedentes_familiares',
        'antecedentes_personales',
        'enfermedades_cronicas',
        'medicamentos_actuales',
        'cirugias_previas',
        'habitos',
        'status'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}
