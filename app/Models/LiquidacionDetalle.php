<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidacionDetalle extends Model
{
    use HasFactory;

    protected $table = 'liquidacion_detalles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'liquidacion_id',
        'factura_total_id',
        'status'
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'liquidacion_id');
    }

    public function facturaTotal()
    {
        return $this->belongsTo(FacturaTotal::class, 'factura_total_id');
    }
}
