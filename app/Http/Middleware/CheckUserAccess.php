<?php

namespace App\Http\Middleware;

use App\Models\Rol;
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

        // Si el usuario no está autenticado, redirigir al login
        if (!$user) {
            return redirect()->route('login');
        }

        // Obtener las rutas permitidas para el usuario según su rol
        $allowedRoutes = Rol::accesos_by_id($user['id_rol'])->pluck('ruta')->toArray();
        
        // Ruta actual sin el prefijo de dominio
        $currentRoute = $request->path();
        
        // Si la ruta está permitida, continuar
        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // URL anterior
        $previousUrl = url()->previous();
        $previousRoute = parse_url($previousUrl, PHP_URL_PATH); // Extraer la ruta
        
        // Si la ruta no esta permitda, Verificar si ruta anterior es una ruta válida para el usuario
        if (in_array(ltrim($previousRoute, '/'), $allowedRoutes)) {
            return redirect($previousUrl);
        }

        // Si no tiene acceso y la URL anterior tampoco es válida, redirigir al perfil
        return redirect()->route('profile');
    }
}



