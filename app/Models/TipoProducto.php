<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoProducto extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_producto';
    protected $primaryKey = 'id_tipo_producto';
    protected $fillable = [

        'id_tipo_producto',
        'nombre',
        'estado'

    ];
    #endregion

    public static function get_tipo_producto_by_id($id_tipo_producto): ?TipoProducto
    {
        $result = DB::select("EXEC sp_get_tipo_producto_by_id @id_tipo_producto = ?", [$id_tipo_producto]);
        return $result ? new TipoProducto((array) $result[0]) : null;
    }

    public static function get_tipos_productos(): ?array
    {
        $result = DB::select("EXEC sp_get_tipos_productos");
        return $result ? array_map(function ($item) {
            return new TipoProducto((array) $item);
        }, $result) : null;
    }

    public static function existencia_tipo_producto_by_id($id_tipo_producto): ?bool
    {
        $result = DB::select("EXEC sp_existencia_tipo_producto_by_id @id_tipo_producto = ?", [$id_tipo_producto]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

}
