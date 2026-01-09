<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCabecera extends Model
{
    use HasFactory;

    protected $table = 'factura_cabecera';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cita_id',
        'nro_control',
        'paciente_id',
        'medico_id',
        'tasa_id',
        'fecha_emision',
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

    public function tasa()
    {
        return $this->belongsTo(TasaDolar::class, 'tasa_id');
    }

    public function detalles()
    {
        return $this->hasMany(FacturaDetalle::class, 'cabecera_id');
    }

    public function totales()
    {
        return $this->hasMany(FacturaTotal::class, 'cabecera_id');
    }
}
