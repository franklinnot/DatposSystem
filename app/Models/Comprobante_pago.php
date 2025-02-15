<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante_pago extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'comprobante_pago';
    protected $primaryKey = 'id_comprobante_pago';
    protected $fillable = [
        'id_comprobante_pago',
        'codigo',
        'estado',
        'id_tipo_comprobante',
        'id_venta',
        'id_empresa',
    ];
    #endregion

}
