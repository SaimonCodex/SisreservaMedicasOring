<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
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
        'nro_colegiatura',
        'formacion_academica',
        'experiencia_profesional',
        'experiencia_profesional',
        'status',
        'foto_perfil',
        'banner_perfil',
        'banner_color',
        'tema_dinamico'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

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
        return $this->belongsToMany(Especialidad::class, 'medico_especialidad', 'medico_id', 'especialidad_id')
                    ->withPivot('tarifa', 'atiende_domicilio', 'tarifa_extra_domicilio', 'anos_experiencia', 'status');
    }

    public function consultorios()
    {
        return $this->belongsToMany(Consultorio::class, 'medico_consultorio', 'medico_id', 'consultorio_id')
                    ->withPivot('dia_semana', 'turno', 'horario_inicio', 'horario_fin', 'status');
    }

    public function horarios()
    {
        return $this->hasMany(MedicoConsultorio::class, 'medico_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    public function evolucionesClinicas()
    {
        return $this->hasMany(EvolucionClinica::class, 'medico_id');
    }

    public function ordenesMedicas()
    {
        return $this->hasMany(OrdenMedica::class, 'medico_id');
    }

    public function facturasPacientes()
    {
        return $this->hasMany(FacturaPaciente::class, 'medico_id');
    }

    public function facturasCabecera()
    {
        return $this->hasMany(FacturaCabecera::class, 'medico_id');
    }

    public function configuracionesReparto()
    {
        return $this->hasMany(ConfiguracionReparto::class, 'medico_id');
    }

    public function fechasIndisponibles()
    {
        return $this->hasMany(FechaIndisponible::class, 'medico_id');
    }

    public function solicitudesHistorialSolicitante()
    {
        return $this->hasMany(SolicitudHistorial::class, 'medico_solicitante_id');
    }

    public function solicitudesHistorialPropietario()
    {
        return $this->hasMany(SolicitudHistorial::class, 'medico_propietario_id');
    }
}
