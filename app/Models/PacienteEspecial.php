<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacienteEspecial extends Model
{
    use HasFactory;

    protected $table = 'pacientes_especiales';
    protected $primaryKey = 'id';
    protected $fillable = [
        'paciente_id',
        'tipo',
        'observaciones',
        'status'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function representantes()
    {
        return $this->belongsToMany(Representante::class, 'representante_paciente_especial', 'paciente_especial_id', 'representante_id')
                    ->withPivot('tipo_responsabilidad', 'status');
    }
}
