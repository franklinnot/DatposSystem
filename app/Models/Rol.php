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
    public static function get_rol($id_rol): ?Rol
    {
        $result = DB::select("EXEC sp_get_rol_by_id @id_rol = ?", [$id_rol]);
        return $result ? new Rol((array) $result[0]) : null;
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

    public static function get_accesos_rol_by_id($id_rol): ?array
    {
        $result = DB::select("EXEC sp_get_accesos_by_id_rol @id_rol = ?", [$id_rol]);
        return $result ? array_map(function ($item) {
            return new Acceso((array) $item);  // Conversión a Acceso
        }, $result) : null;  // Array de Acceso[] o null
    }
    #endregion

}
