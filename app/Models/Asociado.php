<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asociado extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'asociado';
    protected $primaryKey = 'id_asociado';
    protected $fillable = [
        'id_asociado',
        'ruc',
        'dni',
        'nombre',
        'telefono',
        'correo',
        'tipo_asociado',
        'estado',
        'id_empresa',
    ];
    #endregion

}
