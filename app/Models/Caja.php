<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'caja';
    protected $primaryKey = 'id_caja';
    protected $fillable = [
        'id_caja',
        'codigo',
        'nombre',
        'estado',
        'id_sucursal',
        'id_empresa',
    ];
    #endregion


}
