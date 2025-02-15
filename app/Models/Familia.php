<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'familia';
    protected $primaryKey = 'id_familia';
    protected $fillable = [
        'id_familia',
        'codigo',
        'nombre',
        'color',
        'estado',
        'id_empresa',
    ];
    #endregion

}
