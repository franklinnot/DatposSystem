<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Constraint\Operator;

class Operacion extends Model
{
    use HasFactory;
    #region Setup del modelo
    protected $table = 'operacion';
    protected $primaryKey = 'id_operacion';
    protected $fillable = [

        'id_operacion',
        'serie',
        'numero',
        'codigo',
        'fecha_registro',
        'fecha_actualizacion',
        'estado',
        'id_usuario',
        'id_almacen_origen',
        'id_almacen_destino',
        'id_tipo_operacion',
        'id_asociado',
        'id_empresa'

    ];
    #endregion
    public static function registrar(array $data): ?Operacion
    {
        // Convertir el array de detalles en una cadena JSON
        $detalleJson = json_encode(array_map(function ($detalle) {
            return [
                'id_producto' => $detalle['id_producto'],
                'costo_unitario' => $detalle['costo_unitario'] ?? null,
                'cantidad' => $detalle['cantidad']
            ];
        }, $data['detalles']));
        
        // Realizar la ejecución del procedimiento almacenado
        $result = DB::select(
            "EXEC sp_registrar_operacion 
            @id_usuario = ?, @id_tipo_operacion = ?, @id_almacen_origen = ?, 
            @id_almacen_destino = ?, @id_asociado = ?, @id_empresa = ?, @detalles = ?",
            [
                $data['id_usuario'],
                $data['id_tipo_operacion'],
                $data['id_almacen_origen'] ?? null,
                $data['id_almacen_destino'] ?? null,
                $data['id_asociado'] ?? null,
                $data['id_empresa'],
                $detalleJson
            ]
        );

        // Si el procedimiento almacenado devuelve un resultado con el nuevo ID
        if ($result && isset($result[0]->nuevo_id)) {
            // Create a new instance with the returned ID and the original data
            $operacion = new Operacion();
            $operacion->id_operacion = $result[0]->nuevo_id;
            $operacion->codigo = $result[0]->codigo;
            // Set other properties as needed
            return $operacion;
        }

        return null;
    }

}
