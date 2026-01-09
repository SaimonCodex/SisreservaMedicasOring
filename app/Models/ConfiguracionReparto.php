<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionReparto extends Model
{
    use HasFactory;

    protected $table = 'configuracion_reparto';
    protected $primaryKey = 'id';
    protected $fillable = [
        'medico_id',
        'consultorio_id',
        'porcentaje_medico',
        'porcentaje_consultorio',
        'porcentaje_sistema',
        'observaciones',
        'status'
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'consultorio_id');
    }
}
