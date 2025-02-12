<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable // implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    // atributos que pueden ser asignados por los metodos create y update
    protected $fillable = [
        'email',
        'password',
        'nombre',
        'direccion',
        'foto',
        'estado',
        'id_rol',
        'id_sucursal',
        'id_almacen',
        'id_empresa',
    ];

    // nombre del identificador unico del usuario => el pk
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    // datos que deben ser ocultados
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}