<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMedica extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio;

    protected $table = 'ordenes_medicas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'medico_id',
        'tipo_orden',
        'descripcion_detallada',
        'indicaciones',
        'resultados',
        'fecha_emision',
        'fecha_vigencia',
        'status'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
