<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anulacion extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'anulacion';
    protected $primaryKey = 'id_anulacion';
    protected $fillable = [
        'id_anulacion',
        'motivo',
        'fecha_anulacion',
        'id_comprobante_pago',
        'id_empresa',
    ];
    #endregion
    
}
