<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retiro_dinero extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'retiro_dinero';
    protected $primaryKey = 'id_retiro_dinero';
    protected $fillable = [
        'id_retiro_dinero',
        'fecha_retiro',
        'saldo_actual',
        'monto_retirado',
        'saldo_restante',
        'id_turno_caja',
        'id_empresa',
    ];
    #endregion

}
