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
use Illuminate\Validation\Rule;
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
        $unidades = array_filter($unidades, fn($unidad) => $unidad->estado == '1');

        // Transformar las familias en el nuevo formato
        $resultado = array_map(function ($familia) {
            $tipo_producto = TipoProducto::get_tipo_producto_by_id($familia->id_tipo_producto);
            return [
                'id'   => $familia->id_familia,
                'name' => $familia->nombre,
                'tipo' => strtolower($tipo_producto->nombre),
            ];
        }, $familias);

        return Inertia::render(
            self::COMPONENTE,
            [
                'familias' => $resultado,
                'unidades' => $unidades,
            ]
        );
    }


    public function store(Request $request): Response
    {
        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_producto = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => [
                'required', 
                'string', 
                'max:128',
                Rule::unique('producto')->where(function ($query) use ($id_empresa) {
                    return $query->where('id_empresa', $id_empresa);
                }),
            ],
            'id_familia' => [
                'required',
                'integer',
                Rule::exists('familia', 'id_familia')->where(function ($query) use ($id_empresa) {
                    return $query->where('id_empresa', $id_empresa);
                }),
            ],
            'imagen' => 'nullable|image',
            'isc' => 'nullable|numeric|min:0',
            'tiene_igv' => 'required|boolean',
            //
            'id_unidad_medida' => [
                'nullable',
                'integer',
                Rule::exists('unidad_medida', 'id_unidad_medida')->where(function ($query) use ($id_empresa) {
                    return $query->where('id_empresa', $id_empresa);
                }),
            ],
            'stock_minimo' => 'nullable|numeric|min:0',
            'stock_maximo' => 'nullable|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date',
            'alerta_stock' => 'nullable|boolean',
            'alerta_vencimiento' => 'nullable|boolean',
        ]);

        $tipo = TipoProducto::get_tipo_producto_by_id($data_producto['id_familia']);
        if(!$tipo){
            $this->error();
        }
        else if(strtolower($tipo->nombre) == 'bien' && !$data_producto['id_unidad_medida']){
            $this->error();
        }
        return Inertia::render(self::COMPONENTE);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar su nuevo producto.',
            ]
        ]);
    }

}
