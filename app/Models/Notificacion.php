<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'receptor_id',
        'receptor_rol',
        'tipo',
        'titulo',
        'mensaje',
        'via',
        'estado_envio',
        'error_detalle',
        'status'
    ];
}
