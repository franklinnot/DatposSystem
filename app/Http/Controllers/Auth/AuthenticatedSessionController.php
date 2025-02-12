<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Empresa;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseIlluminate;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): ResponseIlluminate
    {
        $request->authenticate();

        // si se autentico el email y contraseña, verificamos los estados de empresa y usuario
        $usuario = Auth::user();
        $empresa = Empresa::get_empresa($usuario->id_empresa);


        if ($empresa->estado == 0) {
            // si la empresa esta inactiva
            $this->destroy($request);
            throw ValidationException::withMessages([
                'email' => trans('auth.empresa_inactive'),
            ]);
        }

        if ($usuario->estado == 0) {
            $this->destroy($request);
            throw ValidationException::withMessages([
                'email' => trans('auth.user_inactive'),
            ]);
        }

        $request->session()->regenerate();

        $request->session()->regenerate();

        // En lugar de redireccionar al home, le decimos a inertia que recargue la
        // pagina (login) y al hacer esto automaticamente se mostrara el dashboard
        // al volver a ejectuar el codigo javascript del componente
        return Inertia::location('login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): ResponseIlluminate
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $referer = $request->headers->get('referer', '/');
        // Se indica que se recargue la página 
        return Inertia::location($referer);
    }
}
