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

    #region Setup del modelo
    
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
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    #endregion
    

    // Atributos y funciones que deben ser ocultadas al serializar el modelo en el data-page
    protected $hidden = [
        'password',
        'remember_token',
        // Relationships 1 - N
        'empresa',
        'rol',
        'sucursal',
        'almacen',
    ];


    #region CRUD
    public static function get_usuario($idUsuario): ?Usuario
    {
        return self::find($idUsuario);
    }
    #endregion


    #region Relationships 1 - N

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_rol');
    }
    
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen');
    }

    #endregion

}