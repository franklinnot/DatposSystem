<?php

namespace App\Http\Middleware;

use App\Models\Rol;
use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return Inertia::location(route('login'));
        }

        // Verificar que el usuario esté activo (estado = 1)
        if ($user->estado != '1') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Inertia::location(route('login'));
        }

        // verificar que la empresa este activa
        $empresa = Empresa::get_empresa_by_id($user->id_empresa);
        if (!$empresa || $empresa->estado == 0) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return Inertia::location(route('login'));
        }

        $currentRoute = rtrim($request->path(), '/');

        // Obtener las rutas permitidas para el usuario según su rol
        $accesos = Rol::get_accesos_rol_by_id($user->id_rol, $user->id_empresa);
        
        // Filtrar los accesos para incluir solo aquellos con estado activo (estado = 1)
        $accesos = array_filter($accesos, function ($acceso) {
            return $acceso->estado == '1';
        });


        // Si no hay accesos definidos para el rol, redirigir al perfil
        if (!$accesos) {
            return Inertia::location(route('profile'));
        }

        // Extraer las rutas permitidas del array de objetos Acceso
        $accesos = array_map(function ($acceso) {
            return $acceso->ruta; 
        }, $accesos);

        // Si la ruta está permitida, continuar
        if (in_array($currentRoute, $accesos)) {
            return $next($request);
        }

        // Si la ruta no es permitida, redirigir siempre al perfil
        return redirect()->route('profile');
    }

}
