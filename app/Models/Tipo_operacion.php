<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_operacion extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_operacion';
    protected $primaryKey = 'id_tipo_operacion';
    protected $fillable = [
        'id_tipo_operacion',
        'codigo',
        'nombre',
        'tipo_operacion',
        'estado',
        'id_empresa',
    ];
    #endregion

}
