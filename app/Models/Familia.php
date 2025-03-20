<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Familia extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'familia';
    protected $primaryKey = 'id_familia';
    protected $fillable = [

        'id_familia',
        'codigo',
        'nombre',
        'color',
        'estado',
        'id_empresa'

    ];
    #endregion

    public static function registrar(array $data): ?Familia
    {
        $result = DB::select(
            "EXEC sp_registrar_familia 
            @codigo = ?, @nombre = ?, @descripcion = ?, @color = ?, @id_tipo_producto = ?, @id_empresa = ?",
            [
                strtoupper($data['codigo']),
                $data['nombre'],
                $data['descripcion'],
                $data['color'] ?? null,
                $data['id_tipo_producto'],
                $data['id_empresa']
            ]
        );

        return $result ? new Familia(['id_almacen' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function existencia_familia_by_codigo($codigo, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_familia_by_codigo @codigo = ?, @id_empresa = ?", [strtoupper($codigo), $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }
}
