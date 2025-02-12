<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    protected $fillable = [
        'ruc',
        'razon_social',
        'nombre_comercial',
        'email',
        'telefono',
        'ciudad',
        'direccion',
        'igv',
        'monto_maximo_boleta',
        'numero_tributario',
        'cantidad_sucursales',
        'cantidad_usuarios',
        'facturacion_electronica',
        'logo',
        'dias_plazo',
        'estado',
    ];

    public static function get_empresa($idEmpresa): ?Empresa
    {
        return self::where('id_empresa', $idEmpresa)->first();
    }

    public static function actualizar_estado($idEmpresa, $estado)
    {
        return self::where('id_empresa', $idEmpresa)->update(['estado' => $estado]);
    }
}
