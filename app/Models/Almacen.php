<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'almacen';
    protected $primaryKey = 'id_almacen';
    public $timestamps = false;
    protected $fillable = [
        'id_almacen',
        'codigo',
        'nombre',
        'departamento',
        'ciudad',
        'direccion',
        'estado',
        'id_empresa'
    ];
    #endregion

    public static function registrar(array $data): ?Almacen
    {
        $result = DB::select(
            "EXEC sp_registrar_almacen 
            @codigo = ?, @nombre = ?, @departamento = ?, @ciudad = ?, @direccion = ?, @id_empresa = ?",
            [
                $data['codigo'],
                $data['nombre'],
                $data['departamento'] ?? null,
                $data['ciudad'] ?? null,
                $data['direccion'] ?? null,
                $data['id_empresa']
            ]
        );

        return $result ? new Almacen(['id_almacen' => $result[0]->nuevo_id] + $data) : null;
    }

}
