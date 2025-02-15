<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    protected $fillable = [
        'id_producto',
        'codigo',
        'codigo_producto_sunat',
        'nombre',
        'stock_minimo',
        'stock_maximo',
        'isc',
        'imagen',
        'recibir_alerta',
        'estado',
        'id_familia',
        'id_tipo_producto',
        'id_unidad_medida',
        'id_empresa',
    ];
    #endregion

}
