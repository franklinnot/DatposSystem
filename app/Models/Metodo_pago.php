<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metodo_pago extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'metodo_pago';
    protected $primaryKey = 'id_metodo_pago';
    protected $fillable = [
        'id_metodo_pago',
        'nombre',
    ];
    #endregion

}
