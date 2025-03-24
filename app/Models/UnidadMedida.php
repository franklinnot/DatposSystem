<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnidadMedida extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'unidad_medida';
    protected $primaryKey = 'id_unidad_medida';
    protected $fillable = [

        'id_unidad_medida',
        'codigo',
        'nombre',
        'descripcion',
        'estado',
        'id_empresa'

    ];
    #endregion


    public static function registrar(array $data): ?UnidadMedida
    {
        $result = DB::select(
            "EXEC sp_registrar_unidad_medida 
            @codigo = ?, @nombre = ?, @descripcion = ?, @id_empresa = ?",
            [
                strtoupper($data['codigo']),
                $data['nombre'],
                $data['descripcion'] ?? null,
                $data['id_empresa']
            ]
        );

        return $result ? new UnidadMedida(['id_unidad_medida' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function existencia_unidad_medida_by_codigo($codigo, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_unidad_medida_by_codigo 
        @codigo = ?, @id_empresa = ?", 
        [
            strtoupper($codigo), 
            $id_empresa
        ]);

        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    public static function existencia_unidad_medida_by_id($id_unidad_medida, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_unidad_medida_by_id @id_unidad_medida = ?, @id_empresa = ?", [$id_unidad_medida, $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    public static function get_unidades_by_id_empresa($id_empresa): ?array
    {
        $result = DB::select("EXEC sp_get_unidades_medida_by_id_empresa @id_empresa = ?", [$id_empresa]);
        return $result ? array_map(function ($item) {
            return new UnidadMedida((array) $item);
        }, $result) : null;
    }

}
