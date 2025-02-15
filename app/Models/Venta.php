<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    protected $fillable = [
        'id_venta',
        'suma_subtotal_bruto',
        'suma_descuento',
        'suma_subtotal',
        'suma_impuesto',
        'suma_total',
        'es_preventa',
        'fecha_venta',
        'fecha_actualizacion',
        'estado',
        'id_turno_caja',
        'id_asociado',
        'id_empresa',
    ];
    #endregion

}
