<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'metodo_pago';
    protected $primaryKey = 'id_metodo';
    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'requiere_confirmacion',
        'status'
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_metodo');
    }
}
