<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rol_id',
        'correo',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        // MD5 aplicado dos veces como solicitaste
        $this->attributes['password'] = md5(md5($value));
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'user_id');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'user_id');
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'user_id');
    }

    public function respuestasSeguridad()
    {
        return $this->hasMany(RespuestaSeguridad::class, 'user_id');
    }

    public function historialPasswords()
    {
        return $this->hasMany(HistorialPassword::class, 'user_id');
    }
}
