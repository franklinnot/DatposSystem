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
    public const COMPONENTE = "Operaciones/NuevaOperacion";
    public const RUTA = "operations/new";

    public function show(): Response
    {
        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        // Se reutiliza la función para obtener los datos comunes
        $commonData = $this->getCommonData($id_empresa);

        return Inertia::render(self::COMPONENTE, $commonData);
    }

    public function store(Request $request): Response
    {
        // Validación básica
        $data = $request->validate([
            'id_tipo_operacion'   => 'required|integer|exists:tipo_operacion,id_tipo_operacion',
            'id_asociado'         => 'nullable|integer|exists:asociado,id_asociado',
            'id_almacen_origen'   => 'nullable|integer|exists:almacen,id_almacen',
            'id_almacen_destino'  => 'nullable|integer|exists:almacen,id_almacen',
            'detalle'             => 'required|array|min:1',
            'detalle.*.id'        => 'required|integer|exists:producto,id_producto',
            'detalle.*.cantidad'  => 'required|numeric|min:0.01',
            'detalle.*.costo_unitario' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        // Obtener el tipo de operación para verificar el tipo de movimiento
        $tipoOperacion = TipoOperacion::where('id_tipo_operacion', $data['id_tipo_operacion'])
            ->where('id_empresa', $id_empresa)
            ->where('estado', '1')
            ->first();

        if (!$tipoOperacion) {
            return $this->error('El tipo de operación seleccionado no es válido.');
        }

        // Validaciones adicionales según el tipo de movimiento
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

        $id_almacen_origen = $data['id_almacen_origen'] ?? null;
        // Para cualquier operación que no sea ingreso, no se enviará costo unitario
        if ($tipoOperacion->tipo_movimiento != 1) {
            foreach ($data['detalle'] as &$detalle) {
                $detalle['costo_unitario'] = null;
                $cantidad = $detalle['cantidad'];
                $id_producto = $detalle['id'];
                $producto = Producto::find($id_producto);
                if (!Producto::verificar_stock_by_id($id_producto, $cantidad, $id_almacen_origen, $id_empresa)) {
                    return $this->error('No hay stock suficiente para el producto: ' . $producto->nombre);
                }
            }
        }

        // Verifica que en transferencia los almacenes sean diferentes
        if (
            !empty($data['id_almacen_origen']) &&
            !empty($data['id_almacen_destino']) &&
            $data['id_almacen_origen'] == $data['id_almacen_destino'] &&
            $tipoOperacion->tipo_movimiento == 3
        ) {
            return $this->error('El almacén de origen y destino no pueden ser el mismo.');
        }

        // Para ingreso se limpia el almacén de origen; y para salida, el de destino
        if ($tipoOperacion->tipo_movimiento == 1) {
            $data['id_almacen_origen'] = null;
        } else if ($tipoOperacion->tipo_movimiento == 2) {
            $data['id_almacen_destino'] = null;
        }

        // Preparar datos para Operacion::registrar
        $operacionData = [
            'id_usuario'           => $user->id_usuario,
            'id_tipo_operacion'    => $data['id_tipo_operacion'],
            'id_almacen_origen'    => $data['id_almacen_origen'],
            'id_almacen_destino'   => $data['id_almacen_destino'],
            'id_asociado'          => $data['id_asociado'],
            'id_empresa'           => $id_empresa,
            'detalles'             => array_map(function ($item) {
                return [
                    'id_producto'   => $item['id'],
                    'cantidad'      => $item['cantidad'],
                    'costo_unitario' => $item['costo_unitario'] ?? null
                ];
            }, $data['detalle'])
        ];

        // Registrar la operación usando tu store procedure
        $operacion = Operacion::registrar($operacionData);

        if (!$operacion) {
            return $this->error('No fue posible registrar la operación. Por favor, inténtelo de nuevo.');
        }

        // Retornar respuesta con el "toast" y los datos iguales a show()
        return Inertia::render(self::COMPONENTE, array_merge(
            $this->getCommonData($id_empresa),
            [
                'toast' => [
                    'type' => 'success',
                    'message' => 'Operación registrada exitosamente! Código: ' . $operacion->codigo,
                ],
            ]
        ));
    }

    /**
     * Método privado que agrupa la lógica para obtener
     * los datos comunes requeridos tanto en show() como en store()
     */
    private function getCommonData($id_empresa): array
    {
        return [
            'tipos_operacion' => $this->getTiposOperacion($id_empresa),
            'asociados'      => $this->getAsociados($id_empresa),
            'almacenes'      => $this->getAlmacenes($id_empresa),
            'productos'      => $this->getProductos($id_empresa),
        ];
    }

    private function getTiposOperacion($id_empresa): array
    {
        $tipos = TipoOperacion::get_tipos_operacion($id_empresa);
        $tipos = array_filter($tipos, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id'              => $item->id_tipo_operacion,
                'name'            => $item->nombre,
                'tipo_movimiento' => $item->tipo_movimiento,
            ];
        }, $tipos);
    }

    private function getAsociados($id_empresa): array
    {
        $asociados = Asociado::get_proveedores($id_empresa);
        $asociados = array_filter($asociados, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id'   => $item->id_asociado,
                'name' => $item->nombre,
            ];
        }, $asociados);
    }

    private function getAlmacenes($id_empresa): array
    {
        $almacenes = Almacen::get_almacenes_by_id_empresa($id_empresa);
        $almacenes = array_filter($almacenes, function ($item) {
            return $item->estado == '1';
        });
        return array_map(function ($item) {
            return [
                'id'   => $item->id_almacen,
                'name' => $item->nombre,
            ];
        }, $almacenes);
    }

    private function getProductos($id_empresa): array
    {
        $productos = Producto::get_productos_tipo_bien($id_empresa);
        $productos = array_filter($productos, function ($item) {
            return $item->estado != '0';
        });
        return array_map(function ($item) {
            return [
                'id'   => $item->id_producto,
                'name' => $item->nombre,
            ];
        }, $productos);
    }

    // Método auxiliar para respuestas de error
    private function error($message = null): Response
    {
        throw ValidationException::withMessages([
            'toast' => $message ?? 'No fue posible registrar la operación. Por favor, inténtelo de nuevo.',
        ]);
    }
}
