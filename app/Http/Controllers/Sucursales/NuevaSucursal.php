<?php

namespace App\Http\Controllers\Sucursales;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevaSucursal extends Controller
{
    //
    public const COMPONENTE = "Sucursales/NuevaSucursal";
    public const RUTA = "stores/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }

    public function store(Request $request): Response
    {
        $data_sucursal = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:128',
            'departamento' => 'required|string|max:128',
            'ciudad' => 'required|string|max:128',
            'direccion' => 'required|string|max:128',
            'telefono' => 'nullable|string|max:128',
        ]);

        $user = Auth::user();
        $data_sucursal['id_empresa'] = $user->id_empresa;

        // obtenemos a la empresa
        $empresa = Empresa::get_empresa_by_id($user->id_empresa);
        
        // verificamos que aun pueda seguir registrando sucursales
        if ($empresa->sucursales_registradas >= $empresa->cantidad_sucursales) {
            return $this->errorLimitRegister();
        }

        // verificar que el código de la sucursal sea único antes de registrar
        if (Sucursal::existencia_sucursal_by_codigo($data_sucursal['codigo'], $data_sucursal['id_empresa'])) {
            return $this->errorSameCode();
        }

        // Datos para registrar el nuevo almacen de stock de tienda
        $data_almacen = array_merge([], $data_sucursal); // una copia del data_sucursal
        $data_almacen['nombre'] = "Inventario de {$data_sucursal['nombre']}";
        $data_almacen['codigo'] = "SCR-{$data_sucursal['codigo']}";

        // verificar que el código del almacén sea único antes de registrar
        if (Almacen::existencia_almacen_by_codigo($data_almacen['codigo'], $data_almacen['id_empresa'])) {
            return $this->errorSameCode();
        }

        // registramos el almacen de la sucursal
        $nuevo_almacen = Almacen::registrar($data_almacen);

        // si no se registró correctamente el almacen
        if (!$nuevo_almacen) {
            return $this->error();
        }

        // tomamos el id del almacen registrado
        $data_sucursal['id_almacen'] = $nuevo_almacen->id_almacen;
        $nueva_sucursal = Sucursal::registrar($data_sucursal);

        // si no se registró correctamente la sucursal
        if (!$nueva_sucursal) {
            // eliminamos el almacen que se registro
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Sucursal registrada exitosamente!',
            ],
        ]);
    }

    public function errorSameCode(): Response
    {
        throw ValidationException::withMessages([
            'codigo' => trans('Sucursales.nueva_sucursal.samecode'),
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('Sucursales.nueva_sucursal.error'),
            ]
        ]);
    }

    public function errorLimitRegister(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('Sucursales.nueva_sucursal.limit_registers'),
            ]
        ]);
    }
}
