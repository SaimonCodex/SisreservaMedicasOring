<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $table = 'consultorios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado_id',
        'ciudad_id',
        'municipio_id',
        'parroquia_id',
        'direccion_detallada',
        'telefono',
        'email',
        'horario_inicio',
        'horario_fin',
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

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'especialidad_consultorio', 'consultorio_id', 'especialidad_id')
                    ->withPivot('status');
    }

    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medico_consultorio', 'consultorio_id', 'medico_id')
                    ->withPivot('dia_semana', 'turno', 'horario_inicio', 'horario_fin', 'status');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'consultorio_id');
    }

    public function fechasIndisponibles()
    {
        return $this->hasMany(FechaIndisponible::class, 'consultorio_id');
    }

    public function configuracionesReparto()
    {
        return $this->hasMany(ConfiguracionReparto::class, 'consultorio_id');
    }
}
