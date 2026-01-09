<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';
    protected $primaryKey = 'id_municipio';
    protected $fillable = [
        'id_estado',
        'municipio',
        'status'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'id_municipio');
    }
}
