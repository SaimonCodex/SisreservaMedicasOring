<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Administrador extends Model
{
    use HasFactory, Notifiable;

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

    public function consultorios()
    {
        return $this->belongsToMany(Consultorio::class, 'administrador_consultorio', 'administrador_id', 'consultorio_id')
                    ->withTimestamps();
    }

    public function getNombreCompletoAttribute()
    {
        return $this->primer_nombre . ' ' . $this->primer_apellido;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->usuario->correo ?? null;
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.Administrador.' . $this->id;
    }
}
