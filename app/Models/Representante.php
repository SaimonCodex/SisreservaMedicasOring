<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Representante extends Model
{
    use HasFactory;

    protected $table = 'representantes';
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
        'estado_id',
        'ciudad_id',
        'municipio_id',
        'parroquia_id',
        'direccion_detallada',
        'prefijo_tlf',
        'numero_tlf',
        'genero',
        'parentesco',
        'status'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    public function pacientesEspeciales()
    {
        return $this->belongsToMany(PacienteEspecial::class, 'representante_paciente_especial', 'representante_id', 'paciente_especial_id')
                    ->withPivot('tipo_responsabilidad', 'status');
    }

    /**
     * Relación: cuenta de paciente asociada a este representante
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Usuario asociado a través de la cuenta de paciente
     */
    public function usuario()
    {
        return $this->hasOneThrough(Usuario::class, Paciente::class, 'id', 'id', 'paciente_id', 'user_id');
    }

    /**
     * Verificar si tiene cuenta de paciente vinculada
     */
    public function tieneAccesoPortal()
    {
        return $this->paciente_id !== null && $this->paciente && $this->paciente->user_id;
    }
}
