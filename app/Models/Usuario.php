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
        'status',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
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

    public function getNombreCompletoAttribute()
    {
        if ($this->administrador) {
            return $this->administrador->primer_nombre . ' ' . $this->administrador->primer_apellido;
        } elseif ($this->medico) {
            return $this->medico->primer_nombre . ' ' . $this->medico->primer_apellido;
        } elseif ($this->paciente) {
            return $this->paciente->primer_nombre . ' ' . $this->paciente->primer_apellido;
        }
        return 'Usuario del Sistema';
    }

    public function getCedulaAttribute()
    {
        if ($this->administrador) {
            return $this->administrador->tipo_documento . '-' . $this->administrador->numero_documento;
        } elseif ($this->medico) {
            return $this->medico->tipo_documento . '-' . $this->medico->numero_documento;
        } elseif ($this->paciente) {
            return $this->paciente->tipo_documento . '-' . $this->paciente->numero_documento;
        }
        return 'N/A';
    }
}
