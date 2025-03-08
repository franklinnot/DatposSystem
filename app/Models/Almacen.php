<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'almacen';
    protected $primaryKey = 'id_almacen';
    public $timestamps = false;
    protected $fillable = [
        'id_almacen',
        'codigo',
        'nombre',
        'departamento',
        'ciudad',
        'direccion',
        'estado',
        'id_empresa'
    ];
    #endregion

    #region crud

    public static function registrar(array $data): ?Almacen
    {
        $result = DB::select(
            "EXEC sp_registrar_almacen 
            @codigo = ?, @nombre = ?, @departamento = ?, @ciudad = ?, @direccion = ?, @id_empresa = ?",
            [
                $data['codigo'],
                $data['nombre'],
                $data['departamento'] ?? null,
                $data['ciudad'] ?? null,
                $data['direccion'] ?? null,
                $data['id_empresa']
            ]
        );

        return $result ? new Almacen(['id_almacen' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_almacen_by_id($id_almacen): ?Almacen
    {
        $result = DB::select("EXEC sp_get_almacen_by_id @id_almacen = ?", [$id_almacen]);
        return $result ? new Almacen((array) $result[0]) : null;
    }

    public static function get_almacen_by_codigo($codigo): ?Almacen
    {
        $result = DB::select("EXEC sp_get_almacen_by_codigo @codigo = ?", [$codigo]);
        return $result ? new Almacen((array) $result[0]) : null;
    }

    public static function existencia_almacen_by_codigo($codigo): ?bool
    {
        $result = DB::select("EXEC sp_existencia_almacen_by_codigo @codigo = ?", [$codigo]);
        if (isset($result[0]->existe)) {
            return $result[0]->existe === 'true';
        }
        return null;
    }

    #endregion
}
