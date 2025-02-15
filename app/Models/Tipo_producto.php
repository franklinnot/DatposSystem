<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_producto extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'tipo_producto';
    protected $primaryKey = 'id_tipo_producto';
    protected $fillable = [
        'id_tipo_producto',
        'nombre',
    ];
    #endregion

}
