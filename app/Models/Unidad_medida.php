<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad_medida extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'unidad_medida';
    protected $primaryKey = 'id_unidad_medida';
    protected $fillable = [
        'id_unidad_medida',
        'codigo_tributario',
        'nombre',
        'estado',
        'id_empresa',
    ];
    #endregion

}
