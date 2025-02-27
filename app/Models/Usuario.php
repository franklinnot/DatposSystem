<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable // implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    #region Setup del modelo
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'email',
        'password',
        'nombre',
        'direccion',
        'foto',
        'estado',
        'id_rol',
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
    
    // Atributos y funciones que deben ser ocultadas al serializar el modelo en el data-page
    protected $hidden = [
        'password',
        'remember_token',
    ];
    #endregion
    

    #region crud
    public static function get_usuario($id_usuario): ?Usuario
    {
        $result = DB::select("EXEC sp_get_usuario_by_id @id_usuario = ?", [$id_usuario]);
        return $result ? new Usuario((array) $result[0]) : null;
    }
    #endregion

    
    #region Relaciones 
    
    public function rol(): ?Rol
    {
        return Rol::get_rol($this->id_rol);
    }

    #endregion

}