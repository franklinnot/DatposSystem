<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    #region Setup del modelo
    protected $table = 'almacen';
    protected $primaryKey = 'id_almacen';
    protected $fillable = [
        'id_almacen',
        'codigo',
        'nombre',
        'estado',
        'id_empresa',
    ];
    #endregion


    public function productos()
    {
        return $this->hasMany(Producto_almacen::class, 'id_almacen');
    }
    public function sucursal()
    {
        return $this->hasMany(Sucursal_almacen::class, 'id_almacen');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario_almacen::class, 'id_almacen');
    } 

    
}
