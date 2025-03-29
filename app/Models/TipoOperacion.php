<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoOperacion extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_operacion';
    protected $primaryKey = 'id_tipo_operacion';
    protected $fillable = [

        'id_tipo_operacion',
        'nombre',
        'serie',
        'tipo_movimiento',
        'descripcion',
        'estado',
        'id_empresa'

    ];
    #endregion

    public static function registrar(array $data): ?TipoOperacion
    {
        $result = DB::select(
            "EXEC sp_registrar_tipo_operacion 
            @nombre = ?, @serie = ?, @tipo_movimiento = ?, @descripcion = ?, @id_empresa = ?",
            [
                $data['nombre'],
                strtoupper($data['serie']),
                $data['tipo_movimiento'],
                $data['descripcion'] ?? null,
                $data['id_empresa'],
            ]
        );

        return $result ? new TipoOperacion(['id_tipo_operacion' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function existencia_tipo_operacion_by_serie($serie, $id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_tipo_operacion_by_serie @serie = ?, @id_empresa = ?", [strtoupper($serie), $id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

}
