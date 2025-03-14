<?php

namespace App\Http\Controllers\Almacenes;

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

class NuevoAlmacen extends Controller
{
    //
    public const COMPONENTE = "Almacenes/NuevoAlmacen";
    public const RUTA = "warehouses/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }

    public function store(Request $request): Response
    {
        $data_almacen = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:24',
            'departamento' => 'required|string|max:64',
            'ciudad' => 'required|string|max:64',
            'direccion' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $data_almacen['id_empresa'] = $user->id_empresa;
        $data_almacen['codigo'] = strtoupper($data_almacen['codigo']);

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

        // si todo sale bien
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Almacén registrado exitosamente!',
            ],
        ]);
    }

    public function errorSameCode(): Response
    {
        throw ValidationException::withMessages([
            'codigo' => trans('almacenes.samecode'),
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('almacenes.error'),
            ]
        ]);
    }

}
