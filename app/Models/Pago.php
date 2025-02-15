<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    protected $fillable = [
        'id_pago',
        'monto_pagado',
        'cambio',
        'numero_tarjeta',
        'estado',
        'id_metodo_pago',
        'id_venta',
        'id_empresa',
    ];
    #endregion

}
