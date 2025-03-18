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

class Authenticate 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return Inertia::location(route('login'));
        }
        else{
            return $next($request);
        }
    }
}
