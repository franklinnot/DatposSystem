<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
