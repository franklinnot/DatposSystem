<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_precios extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'lista_precios';
    protected $primaryKey = 'id_lista_precios';
    protected $fillable = [
        'id_lista_precios',
        'codigo',
        'descripcion',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'es_preferencial',
        'estado',
        'id_sucursal',
        'id_empresa',
    ];
    #endregion

}
