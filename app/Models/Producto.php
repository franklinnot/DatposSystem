<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    protected $fillable = [

        'id_producto',
        'codigo',
        'nombre',
        'stock_minimo',
        'stock_maximo',
        'imagen',
        'alerta_stock',
        'tiene_igv',
        'isc',
        'estado',
        'id_familia',
        'id_unidad_medida',
        'id_empresa'

    ];
    #endregion

    public static function registrar(array $data): ?Producto
    {
        // Validar que existan variantes y no estén vacías.
        $variantes = isset($data['variantes']) && !empty($data['variantes']) ? $data['variantes'] : null;

        // Convertir el array de variantes a JSON tal cual, ya que la estructura es la esperada
        $variantesJson = $variantes ? json_encode($variantes) : null;

        // Ejecutar el procedimiento almacenado con los parámetros adecuados
        $result = DB::select(
            "EXEC sp_registrar_producto 
                @codigo = ?, 
                @nombre = ?, 
                @imagen = ?, 
                @stock_minimo = ?, 
                @stock_maximo = ?, 
                @alerta_stock = ?, 
                @tiene_igv = ?, 
                @isc = ?,
                @id_familia = ?, 
                @id_unidad_medida = ?,
                @id_empresa = ?, 
                @variantes = ?",
            [
                strtoupper($data['codigo']),
                $data['nombre'],
                $data['imagen'] ?? null,
                $data['stock_minimo'] ?? null,
                $data['stock_maximo'] ?? null,
                $data['alerta_stock'] ?? null,
                $data['tiene_igv'],
                $data['isc'],
                $data['id_familia'],
                $data['id_unidad_medida'],
                $data['id_empresa'],
                $variantesJson
            ]
        );

        // Verificar que se haya obtenido un resultado y que tenga el nuevo id
        if ($result && isset($result[0]->nuevo_id)) {
            // Retornar una nueva instancia de Producto combinando el id obtenido con el resto de la data
            return new Producto(array_merge(['id_producto' => $result[0]->nuevo_id], $data));
        } else {
            return null;
        }
    }


    public static function existencia_producto_by_codigo($codigo, $id_empresa): ?bool
    {
        $result = DB::select(
            "EXEC sp_existencia_producto_by_codigo 
            @codigo = ?, @id_empresa = ?",
            [
                strtoupper($codigo),
                $id_empresa
            ]
        );

        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    public static function get_productos_tipo_bien($id_empresa): ?array
    {
        $result = DB::select("EXEC sp_get_productos_tipo_bien @id_empresa = ?", [$id_empresa]);
        return $result ? array_map(function ($item) {
            return new Producto((array) $item);
        }, $result) : null;
    }
}
