<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'duracion_cita_default',
        'color',
        'icono',
        'prioridad',
        'requisitos',
        'observaciones',
        'status'
    ];

    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medico_especialidad', 'especialidad_id', 'medico_id')
                    ->withPivot('tarifa', 'anos_experiencia', 'status');
    }

    public function consultorios()
    {
        return $this->belongsToMany(Consultorio::class, 'especialidad_consultorio', 'especialidad_id', 'consultorio_id')
                    ->withPivot('status');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'especialidad_id');
    }
}
