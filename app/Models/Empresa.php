<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'facturacion_electronica',
        'logo',
        'estado',
        'dias_plazo',
    ];
    #endregion

    // Atributos y funciones que deben ser ocultadas al serializar el modelo en el data-page
    protected $hidden = [
        
    ];

    #region CRUD
    public static function get_empresa($id_empresa): ?Empresa
    {
        return self::find($id_empresa);
    }
    #endregion

    #region Relationships 1 - N
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
