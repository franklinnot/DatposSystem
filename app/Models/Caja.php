<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Caja extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'caja';
    protected $primaryKey = 'id_caja';
    protected $fillable = [

        'id_caja',
        'codigo',
        'nombre',
        'estado',
        'id_sucursal',
        'id_empresa'

    ];
    #endregion

    #region crud

    public static function registrar(array $data): ?Caja
    {
        $result = DB::select(
            "EXEC sp_registrar_caja 
            @codigo = ?, @nombre = ?, @id_sucursal = ?, @id_empresa = ?",
            [
                $data['codigo'],
                $data['nombre'],
                $data['id_sucursal'],
                $data['id_empresa'],
            ]
        );

        return $result ? new Caja(['id_caja' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_caja_by_id($id_caja, $id_empresa): ?Caja
    {
        $result = DB::select("EXEC sp_get_caja_by_id @id_caja = ?, @id_empresa = ?", [$id_caja, $id_empresa]);
        return $result ? new Caja((array) $result[0]) : null;
    }

    // hace falta el store procedure
    public static function get_cajas_by_id_sucursal($id_sucursal): ?array
    {
        $result = DB::select("EXEC sp_get_sucursales_by_id_empresa @id_sucursal = ?", [$id_sucursal]);
        return $result ? array_map(function ($item) {
            return new Sucursal((array) $item);
        }, $result) : null;
    }

    // hace falta el store procedure
    public static function get_cajas_by_id_empresa($id_empresa): ?array
    {
        $result = DB::select("EXEC sp_get_sucursales_by_id_empresa @id_empresa = ?", [$id_empresa]);
        return $result ? array_map(function ($item) {
            return new Sucursal((array) $item);
        }, $result) : null;
    }

    public static function get_caja_by_codigo($codigo, $id_empresa): ?Caja
    {
        $result = DB::select("EXEC sp_get_caja_by_codigo @codigo = ?, @id_empresa = ?", [$codigo, $id_empresa]);
        return $result ? new Caja((array) $result[0]) : null;
    }

    public static function existencia_caja_by_codigo($codigo, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_caja_by_codigo @codigo = ?, @id_empresa = ?", [$codigo, $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    #endregion

}
