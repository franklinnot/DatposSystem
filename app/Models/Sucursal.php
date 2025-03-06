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

}
