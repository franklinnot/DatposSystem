<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Familia;
use App\Models\Producto;
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

        $unidades = array_map(function ($unidad) {
            return [
                'id' => $unidad->id_unidad_medida,
                'name' => $unidad->nombre,
            ];
        }, $unidades);

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

        $data_producto = $request->validate([
            'nombre'             => 'required|string|max:128',
            'codigo'             => 'required|string|max:128',
            'id_familia'         => 'required|integer',
            'imagen'             => 'nullable|image',
            'isc'                => 'nullable|numeric|min:0',
            'tiene_igv'          => 'required|boolean',
            'id_unidad_medida'   => 'nullable|integer',
            'stock_minimo'       => 'nullable|numeric|min:0',
            'stock_maximo'       => 'nullable|numeric|min:0',
            'fecha_vencimiento'  => 'nullable|date',
            'alerta_stock'       => 'nullable|boolean',
            'alerta_vencimiento' => 'nullable|boolean',
            'variantes'          => 'nullable|array',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_producto['id_empresa'] = $id_empresa;

        // verificar que el codigo de la sea único antes de registrar
        if (Producto::existencia_producto_by_codigo($data_producto['codigo'], $id_empresa)) {
            return $this->errorSameCode();
        }

        // verificar que el id_familia exista
        if(!Familia::existencia_familia_by_id($data_producto['id_empresa'], $id_empresa)){
            return $this->error();
        }

        // Verificar si el tipo de producto es correcto en base al contexto
        $tipo = TipoProducto::get_tipo_producto_by_id($data_producto['id_familia']);
        if(!$tipo){
            $this->error();
        }
        else if(strtolower($tipo->nombre) == 'bien'){
            if(!$data_producto['id_unidad_medida']){
                return $this->error();
            }
            else{
                // verificar que el id_unidad_medida exista
                if (!UnidadMedida::existencia_unidad_medida_by_id($data_producto['id_unidad_medida'], $id_empresa)) {
                    return $this->error();
                }
            }

            if(isset($data_producto['stock_minimo']) && isset($data_producto['stock_maximo']) && ($data_producto['stock_minimo'] >= $data_producto['stock_maximo'])){
                return $this->error();
            }

            if (!isset($data_producto['stock_minimo']) && !isset($data_producto['stock_maximo'])) {
                $data_producto['alerta_stock'] = null;
            }

            if (!isset($data_producto['fecha_vencimiento'])) {
                $data_producto['alerta_vencimiento'] = null;
            }

        }
        else if(strtolower($tipo->nombre) == 'servicio'){
            $data_producto['id_unidad_medida'] = null;
            $data_producto['stock_minimo'] = null;
            $data_producto['stock_maximo'] = null;
            $data_producto['fecha_vencimiento'] = null;
            $data_producto['alerta_stock'] = null;
            $data_producto['alerta_vencimiento'] = null;
        }
        else{
            return $this->error();
        }

        // Primero, filtrar las variantes que tengan el campo 'variante' o 'detalles' vacíos
        if (!empty($data_producto['variantes'])) {
            $variantesFiltradas = [];
            foreach ($data_producto['variantes'] as $variante) {
                // Verificar que exista y no esté vacío el campo 'variante'
                if (!isset($variante['variante']) || trim($variante['variante']) === '') {
                    continue;
                }
                // Verificar que exista el campo 'detalles' y que no esté vacío
                if (!isset($variante['detalles']) || empty($variante['detalles'])) {
                    continue;
                }
                $variantesFiltradas[] = $variante;
            }
            $data_producto['variantes'] = $variantesFiltradas;

            // Luego, validar duplicados y procesar cada variante
            $nombresVariantes = [];
            $variantesValidas = [];

            foreach ($data_producto['variantes'] as $variante) {
                // Normalizar el nombre de la variante
                $nombreVariante = trim(strtolower($variante['variante']));

                // Verificar que no se repita la variante
                if (in_array($nombreVariante, $nombresVariantes)) {
                    return $this->error();
                }
                $nombresVariantes[] = $nombreVariante;

                // Procesar los detalles de la variante
                $nombresDetalles = [];
                $detallesValidos = [];
                foreach ($variante['detalles'] as $detalle) {
                    // Verificar que exista y no esté vacío el campo 'detalle'
                    if (!isset($detalle['detalle']) || trim($detalle['detalle']) === '') {
                        continue;
                    }
                    // Normalizar el detalle
                    $nombreDetalle = trim(strtolower($detalle['detalle']));
                    if (in_array($nombreDetalle, $nombresDetalles)) {
                        return $this->error();
                    }
                    $nombresDetalles[] = $nombreDetalle;
                    $detallesValidos[] = $detalle;
                }

                // Si la variante termina sin detalles válidos, se descarta
                if (empty($detallesValidos)) {
                    continue;
                }

                // Actualizamos la variante con solo los detalles válidos
                $variante['detalles'] = $detallesValidos;
                $variantesValidas[] = $variante;
            }

            // Actualizamos la data del producto con las variantes procesadas y válidas
            $data_producto['variantes'] = $variantesValidas;
        }

        // Procesar la imagen: si existe, convertirla a base64
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $contenidoImagen = file_get_contents($imagen->getRealPath());
            $data_producto['imagen'] = base64_encode($contenidoImagen);
        }

        // registramos
        $nuevo_producto = Producto::registrar($data_producto);
        if (!$nuevo_producto) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Producto registrado exitosamente!',
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
                'message' => 'No fue posible registrar su nuevo producto.',
            ]
        ]);
    }

}
