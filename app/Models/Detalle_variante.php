<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_variante extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'detalle_variante';
    protected $primaryKey = 'id_detalle_variante';
    protected $fillable = [
        'id_detalle_variante',
        'nombre',
        'estado',
        'id_variante',
        'id_empresa',
    ];
    #endregion

}
