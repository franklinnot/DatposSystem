<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    use HasFactory;


    #region Setup del modelo
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle_venta';
    protected $fillable = [
        'id_detalle_venta',
        'precio_unitario',
        'cantidad',
        'subtotal_bruto',
        'descuento',
        'subtotal',
        'igv',
        'isc',
        'total',
        'id_venta',
        'id_detalle_lista_precios',
        'id_empresa',
    ];
    #endregion


}
