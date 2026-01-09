<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaCatalogo extends Model
{
    use HasFactory;

    protected $table = 'preguntas_catalogo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pregunta',
        'status'
    ];

    public function respuestasSeguridad()
    {
        return $this->hasMany(RespuestaSeguridad::class, 'pregunta_id');
    }
}
