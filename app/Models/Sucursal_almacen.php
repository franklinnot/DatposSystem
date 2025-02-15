<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal_almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'sucursal_almacen';
    protected $primaryKey = 'id_sucursal_almacen';
    protected $fillable = [
        'id_sucursal_almacen',
        'id_sucursal',
        'id_almacen',
        'id_empresa',
    ];
    #endregion

}
