<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_operacion extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'detalle_operacion';
    protected $primaryKey = 'id_detalle_operacion';
    protected $fillable = [
        'id_detalle_operacion',
        'costo_unitario',
        'cantidad',
        'id_operacion',
        'id_producto',
        'id_empresa',
    ];
    #endregion

}
