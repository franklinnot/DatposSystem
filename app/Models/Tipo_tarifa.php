<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_tarifa extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_tarifa';
    protected $primaryKey = 'id_tipo_tarifa';
    protected $fillable = [
        'id_tipo_tarifa',
        'nombre',
        'monto',
        'duracion_meses',
        'cantidad_sucursales',
        'cantidad_usuarios',
        'estado',
    ];
    #endregion

}
