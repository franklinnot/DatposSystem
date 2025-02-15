<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_comprobante extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_comprobante';
    protected $primaryKey = 'id_tipo_comprobante';
    protected $fillable = [
        'id_tipo_comprobante',
        'codigo',
        'nombre',
        'igv',
        'estado',
        'id_empresa',
    ];
    #endregion


}
