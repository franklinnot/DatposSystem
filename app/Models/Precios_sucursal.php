<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precios_sucursal extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'precios_sucursal';
    protected $primaryKey = 'id_precios_sucursal';
    protected $fillable = [
        'id_precios_sucursal',
        'id_sucursal',
        'id_lista_precios',
        'id_empresa',
    ];
    #endregion

}
