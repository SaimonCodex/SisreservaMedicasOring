<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaSeguridad extends Model
{
    use HasFactory;

    protected $table = 'respuestas_seguridad';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'pregunta_id',
        'respuesta_hash',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(PreguntaCatalogo::class, 'pregunta_id');
    }
}
