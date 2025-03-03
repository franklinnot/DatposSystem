<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'almacen';
    protected $primaryKey = 'id_almacen';
    protected $fillable = [
        'id_almacen',
        'codigo',
        'nombre',
        'departamento',
        'ciudad',
        'direccion',
        'estado',
        'id_empresa'
    ];
    #endregion

}
