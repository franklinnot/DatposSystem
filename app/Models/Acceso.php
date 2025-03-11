<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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


    public static function get_accesos(): ?array
    {
        $result = DB::select("EXEC sp_get_accesos");
        return $result ? array_map(function ($item) {
            return new Acceso((array) $item);
        }, $result) : null;
    }

}
