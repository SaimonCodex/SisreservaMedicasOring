<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administradores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_documento',
        'numero_documento',
        'fecha_nac',
        'estado_id',
        'ciudad_id',
        'municipio_id',
        'parroquia_id',
        'direccion_detallada',
        'prefijo_tlf',
        'numero_tlf',
        'genero',
        'foto_perfil',
        'banner_perfil',
        'banner_color',
        'tema_dinamico',
        'tipo_admin',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    public function pagosConfirmados()
    {
        return $this->hasMany(Pago::class, 'confirmado_por');
    }
}
