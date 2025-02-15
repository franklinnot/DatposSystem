<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = [
        'id_sucursal',
        'nombre',
        'ciudad',
        'direccion',
        'telefono',
        'estado',
        'id_empresa',
    ];
    #endregion

}
