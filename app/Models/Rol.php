<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rol extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    protected $fillable = [
        'id_rol',
        'nombre',
        'estado',
        'id_empresa',
    ];
    #endregion

    #region crud

    public static function registrar(array $data): ?Rol
    {
        // Convertir el array de accesos en una cadena JSON
        $accesosJson = json_encode(array_map(function ($id_acceso) {
            return ['id_acceso' => $id_acceso];
        }, $data['subrutas']));

        // Realizar la ejecución del procedimiento almacenado
        $result = DB::select(
            "EXEC sp_registrar_rol 
            @nombre = :nombre, @accesos = :accesos, @id_empresa = :id_empresa",
            [
                'nombre' => $data['nombre'],
                'accesos' => $accesosJson,
                'id_empresa' => $data['id_empresa'],
            ]
        );

        return $result ? new Rol(['id_rol' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_rol($id_rol): ?Rol
    {
        $result = DB::select("EXEC sp_get_rol_by_id @id_rol = ?", [$id_rol]);
        return $result ? new Rol((array) $result[0]) : null;
    }

    public static function get_accesos_rol_by_id($id_rol): ?array
    {
        $result = DB::select("EXEC sp_get_accesos_by_id_rol @id_rol = ?", [$id_rol]);
        return $result ? array_map(function ($item) {
            return new Acceso((array) $item);  // Conversión a Acceso
        }, $result) : null;  // Array de Acceso[] o null
    }

    public static function existencia_rol_by_nombre($nombre, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_rol_by_nombre @nombre = ?, @id_empresa = ?", [$nombre, $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }
    #endregion


    #region Relaciones
    // usar sp
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }

    public function accesos(): ?array
    {
        $result = DB::select("EXEC sp_get_accesos_by_id_rol @id_rol = ?", [$this->id_rol]);
        return $result ? array_map(function ($item) {
            return new Acceso((array) $item);  // Conversión a Acceso
        }, $result) : null;  // Array de Acceso[] o null
    }
    #endregion

}
