<?php

namespace App\Http\Controllers\Cajas;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
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

        // Filtrar sucursales con estado = 1
        $sucursales = array_filter($sucursales, function ($sucursal) {
            return $sucursal->estado == '1';
        });

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
            'codigo' => 'required|string|max:24',
            'id_sucursal' => 'required|integer',
        ]);

        $user = Auth::user();
        $data_caja['id_empresa'] = $user->id_empresa;
        $data_caja['codigo'] = strtoupper($data_caja['codigo']);

        // verificar que el código de la caja sea único antes de registrar
        if (Caja::existencia_caja_by_codigo($data_caja['codigo'], $data_caja['id_empresa'])) {
            return $this->errorSameCode();
        }

        // si la sucursal no existe
        $sucursal = Sucursal::get_sucursal_by_id($data_caja['id_sucursal'], $data_caja['id_empresa']);
        if (!$sucursal) {
            return $this->error();
        }

        // si la sucursal no está activa
        if ($sucursal->estado != 1) {
            return $this->error();
        }

        // si la sucursal no pertenece a la empresa del usuario
        if ($sucursal->id_empresa != $user->id_empresa) {
            return $this->error();
        }

        // registramos la nueva caja
        $nueva_caja = Caja::registrar($data_caja);

        // si no se registró correctamente la caja
        if (!$nueva_caja) {
            return $this->error();
        }

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
            'codigo' => trans('cajas.samecode'),
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('cajas.error'),
            ]
        ]);
    }
}
