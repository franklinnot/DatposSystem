<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Asociado extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'asociado';
    protected $primaryKey = 'id_asociado';
    protected $fillable = [

        'id_asociado',
        'ruc',
        'dni',
        'nombre',
        'telefono',
        'correo',
        'tipo_asociado',
        'estado',
        'id_empresa'

    ];
    #endregion

    public static function registrar(array $data): ?Asociado
    {
        $result = DB::select(
            "EXEC sp_registrar_asociado 
            @nombre = ?, @tipo_asociado = ?, @ruc = ?, @dni = ?, @email = ?, @telefono = ?, @id_empresa = ?",
            [
                $data['nombre'],
                $data['tipo_asociado'] ?? null,
                $data['ruc'] ?? null,
                $data['dni'] ?? null,
                $data['email'] ?? null,
                $data['telefono'] ?? null,
                $data['id_empresa']
            ]
        );

        return $result ? new Asociado(['id_asociado' => $result[0]->nuevo_id] + $data) : null;
    }

    public static function get_asociados($id_empresa): ?array
    {
        $result = DB::select("EXEC sp_get_asociados @id_empresa = ?", [$id_empresa]);
        return $result ? array_map(function ($item) {
            return new Asociado((array) $item);
        }, $result) : null;
    }

}
