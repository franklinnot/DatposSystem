<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto_almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'producto_almacen';
    protected $primaryKey = 'id_producto_almacen';
    protected $fillable = [
        'id_producto_almacen',
        'stock',
        'costo_unitario',
        'id_producto',
        'id_almacen',
        'id_empresa',
    ];
    #endregion

}
