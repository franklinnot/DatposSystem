<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevoUsuario extends Controller
{
    //
    public const COMPONENTE = "Usuarios/NuevoUsuario";
    public const RUTA = "stores/new";

    public function show(): Response
    {
        $user = Auth::user();
        $roles = Rol::get_roles_by_id_empresa($user->id_empresa);

        // Filtrar roles con estado = 1
        $roles = array_filter($roles, function ($rol) {
            return $rol->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $roles_mapeados = array_map(function ($rol) {
            return [
                'id' => $rol->id_rol,
                'name' => $rol->nombre,
            ];
        }, $roles);

        return Inertia::render(self::COMPONENTE, [
            'roles' => $roles_mapeados,
        ]);
    }
}
