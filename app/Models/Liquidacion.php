<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'entidad_tipo',
        'entidad_id',
        'monto_total_usd',
        'monto_total_bs',
        'metodo_pago',
        'referencia',
        'fecha_pago',
        'observaciones',
        'status'
    ];

    public function detalles()
    {
        return $this->hasMany(LiquidacionDetalle::class, 'liquidacion_id');
    }
}
