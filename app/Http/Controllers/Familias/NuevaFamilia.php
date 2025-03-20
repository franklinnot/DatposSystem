<?php

namespace App\Http\Controllers\Familias;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Familia;
use App\Models\Sucursal;
use App\Models\TipoProducto;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevaFamilia extends Controller
{
    //
    public const COMPONENTE = "Familias/NuevaFamilia";
    public const RUTA = "families/new";

    public function show(): Response
    {
        $tipos = TipoProducto::get_tipos_productos();

        // Filtrar sucursales con estado = 1
        $tipos = array_filter($tipos, function ($tipo) {
            return $tipo->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $tipos_mapeados = array_map(function ($tipo) {
            return [
                'id' => $tipo->id_tipo_producto,
                'name' => $tipo->nombre,
            ];
        }, $tipos);

        return Inertia::render(self::COMPONENTE, [
            'tipos_productos' => $tipos_mapeados,
        ]);
    }

    public function store(Request $request): Response
    {
        $data_familia = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:24',
            'descripcion' => 'nullable|string|max:255',
            'color' => 'nullable|hex_color',
            'id_tipo_producto' => 'required|integer',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_familia['id_empresa'] = $id_empresa;
        $data_familia['codigo'] = strtoupper($data_familia['codigo']);

        // verificar que el código  sea único antes de registrar
        if (Familia::existencia_familia_by_codigo($data_familia['codigo'], $id_empresa)) {
            return $this->errorSameCode();
        }

        // verificar que el id del tipo de producto exista
        if(!TipoProducto::existencia_tipo_producto_by_id($data_familia['id_tipo_producto'])){
            return $this->error();
        }

        // registramos la nueva caja
        $nueva_familia = Familia::registrar($data_familia);

        // si no se registró correctamente la caja
        if (!$nueva_familia) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Familia registrada exitosamente!',
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
                'message' => 'No fue posible registrar su nueva familia.',
            ]
        ]);
    }


}
