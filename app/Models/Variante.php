<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variante extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'variante';
    protected $primaryKey = 'id_variante';
    protected $fillable = [
        'id_variante',
        'nombre',
        'estado',
        'id_producto',
        'id_empresa',
    ];
    #endregion

}
