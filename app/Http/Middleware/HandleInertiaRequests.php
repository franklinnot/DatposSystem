<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Middleware;

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
        $empresa = $this->get_empresaData($user);

        return array_merge($parentData, [
            'auth' => [
                'user' => $user ? $user->toArray() : null,
                'empresa' => $empresa ? $empresa->toArray() : null,
                // 'roles'     => $this->get_rolesData(),

                // 'productos' => $this->get_productosData(),
                // 'ventas'    => $this->get_ventasData(),
                // Puedes agregar más datos de forma modular aquí
            ],
        ]);
    }

    #region DATA que por cada solicitud siempre estara siendo actualizada

    // Obtener el usuario actualizado de la base de datos, si está autenticado
    private function get_usuarioData(Request $request)
    {
        return $request->user() ? $request->user()->fresh() : null;
    }

    // Empresa asociada al usuario autenticado.
    private function get_empresaData($user)
    {
        return $user ? $user->empresa : null;
    }


    // Consulta y retorna la lista actualizada de roles.
    // private function get_rolesData(): array
    // {
    //     return Rol::all()->toArray();
    // }

    #endregion

    // Consulta y retorna la lista actualizada de productos.
    // private function get_productosData(): array
    // {
    //     return Producto::all()->toArray();
    // }

    // Consulta y retorna la lista actualizada de ventas.
    // private function get_ventasData(): array
    // {
    //     return Venta::all()->toArray();
    // }

}
