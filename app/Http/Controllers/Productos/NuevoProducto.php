<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Familia;
use App\Models\Sucursal;
use App\Models\TipoProducto;
use App\Models\UnidadMedida;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevoProducto extends Controller
{
    //
    public const COMPONENTE = "Productos/NuevoProducto";
    public const RUTA = "products/new";

    public function show(): Response
    {
        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        $familias = Familia::get_familias_by_id_empresa($id_empresa);
        $unidades = UnidadMedida::get_unidades_by_id_empresa($id_empresa);

        // Filtrar por registros activos
        $familias = array_filter($familias, fn($familia) => $familia->estado == '1');
        $unidades= array_filter($unidades, fn($unidad) => $unidad->estado == '1');

        // Transformar las familias en el nuevo formato
        $resultado = array_map(function ($familia){
            $tipo_producto = TipoProducto::get_tipo_producto_by_id($familia->id_tipo_producto);
            return [
                'id'   => $familia->id_familia,
                'name' => $familia->nombre,
                'tipo' => strtolower($tipo_producto->nombre),
            ];
        }, $familias);

        return Inertia::render(self::COMPONENTE, 
        [
            'familias' => $resultado,
            'unidades' => $unidades,
        ]);
    }


    public function store(Request $request): Response {
        return Inertia::render(self::COMPONENTE);
    }

}
