<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{

    #region Setup del modelo
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    protected $fillable = [
        'id_empresa',
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
        'sucursales_registradas',
        'usuarios_registrados',
        'facturacion_electronica',
        'logo',
        'estado',
        'dias_plazo',
    ];

    // Atributos y funciones que deben ser ocultadas al serializar el modelo en el data-page
    protected $hidden = [
        
    ];
    #endregion

    #region crud
    public static function get_empresa_by_id($id_empresa): ?Empresa
    {
        $result = DB::select("EXEC sp_get_empresa_by_id @id_empresa = ?", [$id_empresa]);
        return $result ? new Empresa((array) $result[0]) : null;
    }

    public static function existencia_empresa_by_id($id_empresa): ?bool
    {
        $result = DB::select("EXEC sp_existencia_empresa_by_id @id_empresa = ?", [$id_empresa]);
        if (isset($result[0]->verificar)) {
            return $result[0]->verificar === 'true';
        }
        return null;
    }

    #endregion

    #region Relaciones
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_empresa');
    }

    public function sucursales()
    {
        return $this->hasMany(Usuario::class, 'id_empresa');
    }

    public function pagosTarifa()
    {
        return $this->hasMany(Usuario::class, 'id_empresa');
    }
    #endregion

    
}
