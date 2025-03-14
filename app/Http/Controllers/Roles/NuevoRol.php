<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Acceso;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Rol;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevoRol extends Controller
{
    //
    public const COMPONENTE = "Roles/NuevoRol";
    public const RUTA = "roles/new";

    public function show(): Response
    {
        // Obtener los accesos desde el modelo
        $accesos = Acceso::get_accesos();

        // Agrupar las rutas por su ruta padre
        $accesosAgrupados = $this->agruparAccesos($accesos);

        return Inertia::render(self::COMPONENTE, [
            'accesos_sistema' => $accesosAgrupados,
        ]);
    }

    function agruparAccesos($accesos)
    {
        $grupos = [];

        foreach ($accesos as $acceso) {
            // extraer la ruta padre (primera parte antes del primer '/')
            $rutaPadre = explode('/', $acceso->ruta)[0];

            // Si no existe un grupo para esta ruta padre, crearlo
            if (!isset($grupos[$rutaPadre])) {
                $grupos[$rutaPadre] = [
                    'rutaPadre' => $rutaPadre,
                    'subRutas' => [],
                ];
            }

            // Añadir la subruta al grupo correspondiente
            $grupos[$rutaPadre]['subRutas'][] = [
                'id' => $acceso->id_acceso,
                'name' => $acceso->nombre,
                'ruta' => $acceso->ruta,
            ];
        }

        // Convertir el objeto en un array
        return array_values($grupos);
    }

    public function store(Request $request): Response
    {
        $data_rol = $request->validate([
            'nombre' => 'required|string|max:64',
            'subrutas' => 'required|array',
        ]);

        $user = Auth::user();
        $data_rol['id_empresa'] = $user->id_empresa;

        // verificar que el nombre no exista antes de registrar
        if (Rol::existencia_rol_by_nombre($data_rol['nombre'], $data_rol['id_empresa'])) {
            return $this->errorSameName();
        }

        // registramos el nuevo rol
        $nuevo_rol = Rol::registrar($data_rol);

        // si no se registró correctamente la caja
        if (!$nuevo_rol) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Rol registrado exitosamente!',
            ],
        ]);
    }

    public function errorSameName(): Response
    {
        throw ValidationException::withMessages([
            'nombre' => trans('roles.samename'),
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('roles.error'),
            ]
        ]);
    }

}
