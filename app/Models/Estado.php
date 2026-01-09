<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';
    protected $primaryKey = 'id_estado';
    protected $fillable = [
        'estado',
        'iso_3166_2',
        'status'
    ];

    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'id_estado');
    }

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_estado');
    }
}
