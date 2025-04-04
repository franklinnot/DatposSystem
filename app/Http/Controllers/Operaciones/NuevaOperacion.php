<?php

namespace App\Http\Controllers\Operaciones;

use App\Http\Controllers\Controller;
use App\Models\Acceso;
use App\Models\Almacen;
use App\Models\Asociado;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\TipoOperacion;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevaOperacion extends Controller
{
    //
    public const COMPONENTE = "Operaciones/NuevaOperacion";
    public const RUTA = "operations/new";

    public function show(): Response
    {
        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        // tipos de operacion
        $tipos = TipoOperacion::get_tipos_operacion($id_empresa);
        $tipos = array_filter($tipos, function ($item) {
            return $item->estado == '1';
        });
        $tipos_mapeados = array_map(function ($item) {
            return [
                'id' => $item->id_tipo_operacion,
                'name' => $item->nombre,
                'tipo_movimiento' => $item->tipo_movimiento,
            ];
        }, $tipos);

        // asociados
        $asociados = Asociado::get_proveedores($id_empresa);
        $asociados = array_filter($asociados, function ($item) {
            return $item->estado == '1';
        });
        $asociados_mapeados = array_map(function ($item) {
            return [
                'id' => $item->id_asociado,
                'name' => $item->nombre,
            ];
        }, $asociados);

        // almacenes
        $almacenes = Almacen::get_almacenes_by_id_empresa($id_empresa);
        $almacenes = array_filter($almacenes, function ($item) {
            return $item->estado == '1';
        });
        $almacenes_mapeados = array_map(function ($item) {
            return [
                'id' => $item->id_almacen,
                'name' => $item->nombre,
            ];
        }, $almacenes);

        // productos
        $productos = Producto::get_productos_tipo_bien($id_empresa);
        $productos = array_filter($productos, function ($item) {
            return $item->estado != '0';
        });
        $productos_mapeados = array_map(function ($item) {
            return [
                'id' => $item->id_producto,
                'name' => $item->nombre,
            ];
        }, $productos);

        return Inertia::render(self::COMPONENTE, [
            'tipos_operacion' => $tipos_mapeados,
            'asociados' => $asociados_mapeados,
            'almacenes' => $almacenes_mapeados,
            'productos' => $productos_mapeados,
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'id_tipo_operacion' => 'required|integer',
            'id_asociado' => 'nullable|integer',
            'id_almacen_origen' => 'nullable|integer',
            'id_almacen_destino' => 'nullable|integer',
            'detalle' => 'required|array',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Caja registrada exitosamente!',
            ],
        ]);
    }

    public function errorSameCode(): Response
    {
        throw ValidationException::withMessages([
            'codigo' => 'Por favor, intente registrar con otro código.',
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar su nueva caja.',
            ]
        ]);
    }

}
