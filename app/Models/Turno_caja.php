<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno_caja extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'turno_caja';
    protected $primaryKey = 'id_turno_caja';
    protected $fillable = [
        'id_turno_caja',
        'codigo',
        'fecha_apertura',
        'fecha_cierre',
        'saldo_inicial',
        'total_ventas',
        'total_retirado',
        'saldo_facturado',
        'saldo_entregado',
        'diferencia_saldo',
        'estado',
        'id_usuario',
        'id_caja',
        'id_empresa',
    ];
    #endregion

}
