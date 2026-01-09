<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaIndisponible extends Model
{
    use HasFactory;

    protected $table = 'fecha_indisponible';
    protected $primaryKey = 'id';
    protected $fillable = [
        'medico_id',
        'consultorio_id',
        'fecha',
        'motivo',
        'todo_el_dia',
        'hora_inicio',
        'hora_fin',
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
