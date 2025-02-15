<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_lista_precios extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'detalle_lista_precios';
    protected $primaryKey = 'id_detalle_lista_precios';
    protected $fillable = [
        'id_detalle_lista_precios',
        'precio_unitario',
        'descuento_maximo',
        'id_lista_precios',
        'id_producto',
        'id_empresa',
    ];
    #endregion

}
