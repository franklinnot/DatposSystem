<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'usuario_almacen';
    protected $primaryKey = 'id_usuario_almacen';
    protected $fillable = [
        'id_usuario_almacen',
        'id_usuario',
        'id_almacen',
        'id_empresa',
    ];
    #endregion

}
