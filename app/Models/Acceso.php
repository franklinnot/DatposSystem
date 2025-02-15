<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{
    use HasFactory;
    
    #region Setup del modelo
    protected $table = 'acceso';
    protected $primaryKey = 'id_acceso';
    protected $fillable = [
        'id_acceso',
        'nombre',
        'ruta',
        'estado',
    ];
    #endregion

    public function accesos_rol()
    {
        return $this->hasMany(Acceso_rol::class, 'id_acceso');
    } 
}
