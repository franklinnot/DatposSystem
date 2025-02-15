<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago_tarifa extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'pago_tarifa';
    protected $primaryKey = 'id_pago_tarifa';
    protected $fillable = [
        'id_pago_tarifa',
        'fecha_pago',
        'fecha_inicio',
        'fecha_renovacion',
        'duracion_meses',
        'monto',
        'estado',
        'id_tipo_tarifa',
        'id_empresa',
    ];
    #endregion


}
