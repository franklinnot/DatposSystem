<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    // agregar bool para determinar la sucursal principal
    #region Setup del modelo
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = [
        'id_sucursal',
        'codigo',
        'nombre',
        'departamento',
        'ciudad',
        'direccion',
        'telefono',
        'estado',
        'id_almacen',
        'id_empresa',
    ];
    #endregion

}
