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
        'fecha_vencimiento',
        'alerta_stock',
        'alerta_vencimiento',
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
        $result = DB::select(
            "EXEC sp_registrar_producto 
            @codigo = ?, @nombre = ?, 
            @stock_minimo = ?, @stock_maximo = ?, 
            @imagen = ?, @fecha_vencimiento = ?, 
            @alerta_stock = ?, @alerta_vencimiento = ?, 
            @tiene_igv = ?, @isc = ?,
            @id_familia = ?, @id_unidad_medida = ?,
            @id_empresa = ?",
            [
                strtoupper($data['codigo']),
                $data['nombre'],
                $data['stock_minimo'] ?? null,
                $data['stock_maximo'] ?? null,
                $data['imagen'] ?? null,
                $data['fecha_vencimiento'] ?? null,
                $data['alerta_stock'],
                $data['alerta_vencimiento'],
                $data['tiene_igv'],
                $data['isc'],
                $data['id_familia'],
                $data['id_unidad_medida'],
                $data['id_empresa'],
            ]
        );

        return $result ? new Producto(['id_producto' => $result[0]->nuevo_id] + $data) : null;
    }

}
