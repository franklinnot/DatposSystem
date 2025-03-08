<?php

namespace App\Http\Middleware;

use App\Models\Rol;
use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return redirect()->route('login');
        }

        // Obtener la empresa y verificar la restricción de "stores/new"
        $empresa = Empresa::get_empresa_by_id($user['id_empresa']);
        $currentRoute = rtrim($request->path(), '/');

        if ($empresa && $empresa->cantidad_sucursales == $empresa->sucursales_registradas && $currentRoute === 'stores/new') {
            return redirect()->route('profile');
        }

        // Obtener las rutas permitidas para el usuario según su rol
        $allowedRoutes = Rol::accesos_by_id($user['id_rol'])->pluck('ruta')->toArray();

        // Si la ruta está permitida, continuar
        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // Si la ruta no es permitida, redirigir siempre al perfil
        return redirect()->route('profile');
    }
}
