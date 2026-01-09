<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPassword extends Model
{
    use HasFactory;

    protected $table = 'historial_password';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'password_hash',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
