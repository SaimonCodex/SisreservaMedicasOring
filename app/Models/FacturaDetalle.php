<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    use HasFactory;

    protected $table = 'factura_detalles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cabecera_id',
        'entidad_tipo',
        'entidad_id',
        'descripcion',
        'cantidad',
        'precio_unitario_usd',
        'subtotal_usd',
        'status'
    ];

    public function cabecera()
    {
        return $this->belongsTo(FacturaCabecera::class, 'cabecera_id');
    }
}
