<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaTotal extends Model
{
    use HasFactory;

    protected $table = 'factura_totales';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cabecera_id',
        'entidad_tipo',
        'entidad_id',
        'base_imponible_usd',
        'impuestos_usd',
        'total_final_usd',
        'total_final_bs',
        'estado_liquidacion',
        'status'
    ];

    public function cabecera()
    {
        return $this->belongsTo(FacturaCabecera::class, 'cabecera_id');
    }

    public function liquidacionDetalles()
    {
        return $this->hasMany(LiquidacionDetalle::class, 'factura_total_id');
    }
}
