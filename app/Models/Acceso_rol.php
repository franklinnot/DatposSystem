<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso_rol extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'acceso_rol';
    protected $primaryKey = 'id_acceso_rol';
    protected $fillable = [
        'id_acceso_rol',
        'id_acceso',
        'id_rol',
        'id_empresa',
    ];
    #endregion
}
