<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Acceso;
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
            // Extraer la ruta padre (primera parte antes del primer '/')
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

}
