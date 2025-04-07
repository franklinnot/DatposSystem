<?php

namespace App\Http\Controllers\Operaciones;

use App\Http\Controllers\Controller;
use App\Models\Acceso;
use App\Models\Almacen;
use App\Models\Asociado;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Operacion;
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
        // Basic validation
        $data = $request->validate([
            'id_tipo_operacion' => 'required|integer|exists:tipo_operacion,id_tipo_operacion',
            'id_asociado' => 'nullable|integer|exists:asociado,id_asociado',
            'id_almacen_origen' => 'nullable|integer|exists:almacen,id_almacen',
            'id_almacen_destino' => 'nullable|integer|exists:almacen,id_almacen',
            'detalle' => 'required|array|min:1',
            'detalle.*.id' => 'required|integer|exists:producto,id_producto',
            'detalle.*.cantidad' => 'required|numeric|min:0.01',
            'detalle.*.costo_unitario' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        // Get tipo_operacion to check tipo_movimiento
        $tipoOperacion = TipoOperacion::where('id_tipo_operacion', $data['id_tipo_operacion'])
            ->where('id_empresa', $id_empresa)
            ->where('estado', '1')
            ->first();

        if (!$tipoOperacion) {
            return $this->error('El tipo de operación seleccionado no es válido.');
        }

        // Additional validations based on tipo_movimiento
        switch ($tipoOperacion->tipo_movimiento) {
            case 1: // Entrada
                if (empty($data['id_almacen_destino'])) {
                    return $this->error('Para una operación de entrada, debe seleccionar un almacén de destino.');
                }
                break;
            case 2: // Salida
                if (empty($data['id_almacen_origen'])) {
                    return $this->error('Para una operación de salida, debe seleccionar un almacén de origen.');
                }
                break;
            case 3: // Transferencia
                if (empty($data['id_almacen_origen']) || empty($data['id_almacen_destino'])) {
                    return $this->error('Para una operación de transferencia, debe seleccionar almacenes de origen y destino.');
                }
                break;
        }

        // Check if origin and destination are different when both are provided
        if (
            !empty($data['id_almacen_origen']) && !empty($data['id_almacen_destino'])
            && $data['id_almacen_origen'] == $data['id_almacen_destino']
        ) {
            return $this->error('El almacén de origen y destino no pueden ser el mismo.');
        }

        // Prepare data for Operacion::registrar
        $operacionData = [
            'id_usuario' => $user->id_usuario,
            'id_tipo_operacion' => $data['id_tipo_operacion'],
            'id_almacen_origen' => $data['id_almacen_origen'],
            'id_almacen_destino' => $data['id_almacen_destino'],
            'id_asociado' => $data['id_asociado'],
            'id_empresa' => $id_empresa,
            'detalles' => array_map(function ($item) {
                return [
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'costo_unitario' => $item['costo_unitario'] ?? null
                ];
            }, $data['detalle'])
        ];

        // Register the operation
        $operacion = Operacion::registrar($operacionData);

        if (!$operacion) {
            return $this->error('No fue posible registrar la operación. Por favor, inténtelo de nuevo.');
        }

        // Return success response
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Operación registrada exitosamente! Código: ' . $operacion->codigo,
            ],
        ]);
    }

    // Helper method for error responses
    private function error($message = null): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => $message ?? 'No fue posible registrar la operación.',
            ],
        ]);
    }

    // Helper methods to get data for the form
    private function getTiposOperacion($id_empresa)
    {
        $tipos = TipoOperacion::get_tipos_operacion($id_empresa);
        $tipos = array_filter($tipos, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id' => $item->id_tipo_operacion,
                'name' => $item->nombre,
                'tipo_movimiento' => $item->tipo_movimiento,
            ];
        }, $tipos);
    }

    private function getAsociados($id_empresa)
    {
        $asociados = Asociado::get_proveedores($id_empresa);
        $asociados = array_filter($asociados, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id' => $item->id_asociado,
                'name' => $item->nombre,
            ];
        }, $asociados);
    }

    private function getAlmacenes($id_empresa)
    {
        $almacenes = Almacen::get_almacenes_by_id_empresa($id_empresa);
        $almacenes = array_filter($almacenes, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id' => $item->id_almacen,
                'name' => $item->nombre,
            ];
        }, $almacenes);
    }

    private function getProductos($id_empresa)
    {
        $productos = Producto::get_productos_tipo_bien($id_empresa);
        $productos = array_filter($productos, function ($item) {
            return $item->estado != '0';
        });
        return array_map(function ($item) {
            return [
                'id' => $item->id_producto,
                'name' => $item->nombre,
            ];
        }, $productos);
    }

}
