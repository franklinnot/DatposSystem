<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sucursal extends Model
{
    use HasFactory;

    // agregar bool para determinar la sucursal principal
    #region Setup del modelo
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = [
        'id_sucursal',
        'codigo',
        'nombre',
        'departamento',
        'ciudad',
        'direccion',
        'telefono',
        'estado',
        'id_almacen',
        'id_empresa',
    ];
    #endregion


    #region crud

    public static function registrar(array $data): ?Sucursal
    {
        $result = DB::select(
            "EXEC sp_registrar_sucursal 
            @codigo = ?, @nombre = ?, @departamento = ?, @ciudad = ?, @direccion = ?, @telefono = ?, @id_almacen = ?, @id_empresa = ?",
            [
                $data['codigo'],
                $data['nombre'],
                $data['departamento'] ?? null,
                $data['ciudad'] ?? null,
                $data['direccion'] ?? null,
                $data['telefono'] ?? null,
                $data['id_almacen'],
                $data['id_empresa'],
            ]
        );

        return $result ? new Sucursal(['id_sucursal' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_sucursal_by_id($id_sucursal): ?Sucursal
    {
        $result = DB::select("EXEC sp_get_sucursal_by_id @id_sucursal = ?", [$id_sucursal]);
        return $result ? new Sucursal((array) $result[0]) : null;
    }

    public static function get_sucursales_by_id_empresa($id_empresa): ?array
    {
        $result = DB::select("EXEC sp_get_sucursales_by_id_empresa @id_empresa = ?", [$id_empresa]);
        return $result ? array_map(function ($item) {
            return new Sucursal((array) $item);
        }, $result) : null;
    }

    public static function get_sucursal_by_codigo($codigo, $id_empresa): ?Sucursal
    {
        $result = DB::select("EXEC sp_get_sucursal_by_codigo @codigo = ?, @id_empresa = ?", [$codigo, $id_empresa]);
        return $result ? new Sucursal((array) $result[0]) : null;
    }

    public static function existencia_sucursal_by_codigo($codigo, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_sucursal_by_codigo @codigo = ?, @id_empresa = ?", [$codigo, $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    #endregion

}
