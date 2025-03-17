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
        'id_usuario',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'nombre',
        'estado',
        'id_rol',
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
    
    // Atributos y funciones que deben ser ocultadas al serializar el modelo en el data-page
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
    #endregion


    #region crud

    public static function registrar(array $data): ?Usuario
    {
        // Convertir el array de accesos en una cadena JSON
        $sucursalesJson = json_encode(array_map(function ($id_sucursal) {
            return ['id_sucursal' => $id_sucursal];
        }, $data['sucursales']));

        // Convertir el array de accesos en una cadena JSON
        $almacenesJson = json_encode(array_map(function ($id_almacen) {
            return ['id_almacen' => $id_almacen];
        }, $data['almacenes']));

        $result = DB::select(
            "EXEC sp_registrar_usuario 
            @dni = ?, @nombre = ?, @email = ?, @password = ?, @id_rol = ?, @sucursales = ?, @almacenes = ?,@id_empresa = ?",
            [
                $data['dni'],
                $data['nombre'],
                strtolower($data['email']) ?? null,
                $data['password'] ?? null,
                $data['id_rol'] ?? null,
                $sucursalesJson,
                $almacenesJson,
                $data['id_empresa']
            ]
        );

        return $result ? new Usuario(['id_usuario' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_usuario_by_id($id_usuario, $id_empresa): ?Usuario
    {
        $result = DB::select("EXEC sp_get_usuario_by_id @id_usuario = ?, @id_empresa = ?", [$id_usuario, $id_empresa]);
        return $result ? new Usuario((array) $result[0]) : null;
    }

    public static function get_usuario_by_dni($dni, $id_empresa): ?Usuario
    {
        $result = DB::select("EXEC sp_get_usuario_by_dni @dni = ?, @id_empresa = ?", [$dni, $id_empresa]);
        return $result ? new Usuario((array) $result[0]) : null;
    }

    public static function existencia_usuario_by_dni($dni, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_usuario_by_dni @dni = ?, @id_empresa = ?", [$dni, $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    public static function existencia_usuario_by_email($email): ?bool
    {
        $result = DB::select("EXEC sp_existencia_usuario_by_email @email = ?", [strtolower($email)]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    #endregion

}