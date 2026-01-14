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
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_documento',
        'numero_documento',
        'fecha_nac',
        'tiene_documento',
        'estado_id',
        'ciudad_id',
        'municipio_id',
        'parroquia_id',
        'direccion_detallada',
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

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->primer_nombre . ' ' . $this->segundo_nombre . ' ' . $this->primer_apellido . ' ' . $this->segundo_apellido);
    }
}

