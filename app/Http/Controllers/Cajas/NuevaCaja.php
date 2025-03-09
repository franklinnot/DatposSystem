<?php

namespace App\Http\Controllers\Cajas;

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


class NuevaCaja extends Controller
{
    //
    public const COMPONENTE = "Cajas/NuevaCaja";
    public const RUTA = "cashregisters/new";

    public function show(): Response
    {
        $user = Auth::user();
        $sucursales = Sucursal::get_sucursales_by_id_empresa($user->id_empresa);

        // Mapear los datos a la estructura requerida
        $sucursales_mapeadas = array_map(function ($sucursal) {
            return [
                'id' => $sucursal->id_sucursal,
                'name' => $sucursal->nombre,
            ];
        }, $sucursales);

        return Inertia::render(self::COMPONENTE, [
            'sucursales' => $sucursales_mapeadas,
        ]);
    }

    public function store(Request $request): Response
    {
        $data_caja = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:128',
            'id_sucursal' => 'required|integer',
        ]);

        $user = Auth::user();
        $data_caja['id_empresa'] = $user->id_empresa;

        // verificar que el código de la sucursal sea único antes de registrar
        if (Sucursal::existencia_sucursal_by_codigo($data_caja['codigo'], $data_caja['id_empresa'])) {
            return $this->errorSameCode();
        }

        // Datos para registrar el nuevo almacen de stock de tienda
        $data_almacen = array_merge([], $data_caja); // una copia del data_caja
        $data_almacen['nombre'] = "Inventario de {$data_caja['nombre']}";
        $data_almacen['codigo'] = "SCR-{$data_caja['codigo']}";

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
        $data_caja['id_almacen'] = $nuevo_almacen->id_almacen;
        $nueva_sucursal = Sucursal::registrar($data_caja);

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
            'codigo' => trans('nueva_sucursal.samecode'),
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('nueva_sucursal.error'),
            ]
        ]);
    }

    public function errorLimitRegister(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('nueva_sucursal.limit_registers'),
            ]
        ]);
    }
}
