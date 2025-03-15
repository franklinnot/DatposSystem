<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Inertia\Inertia;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    /**
     * Comparte datos globales con Inertia.
     */
    public function share(Request $request): array
    {
        $parentData = parent::share($request);

        // DATA que por cada solicitud siempre estara siendo actualizada
        $user = $this->get_usuarioData($request);
        $usuarioAccesos = $this->get_usuarioAccesosData($user);
        $empresa = $this->get_empresaData($user);

        return array_merge($parentData, [
            'auth' => [
                'user' => $user ? $user->toArray() : null,
                'accesos' => $usuarioAccesos ? $usuarioAccesos : null,
                'empresa' => $empresa ? $empresa->toArray() : null,
            ],
        ]);
    }

    #region DATA que por cada solicitud siempre estara siendo actualizada

    // Obtener el usuario actualizado de la base de datos, si está autenticado
    private function get_usuarioData(Request $request)
    {
        return $request->user() ? $request->user() : null;
    }

    // obtener los accesos del usuario
    private function get_usuarioAccesosData($user)
    {
        if (!$user) {
            return null;
        }

        $accesos = Rol::get_accesos_rol_by_id($user['id_rol'], $user['id_empresa']);

        return array_map(function ($acceso) {
            return [
                'id_acceso' => $acceso['id_acceso'],
                'ruta' => $acceso['ruta']
            ];
        }, $accesos);
    }

    // Empresa asociada al usuario autenticado.
    private function get_empresaData($user)
    {
        return $user ? Empresa::get_empresa_by_id($user['id_empresa']) : null;
    }

    #endregion

}
