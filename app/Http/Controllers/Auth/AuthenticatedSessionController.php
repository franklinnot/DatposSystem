<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseIlluminate;

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

        $request->session()->regenerate();

        // En lugar de redireccionar con redirect()->intended(), se le indica a Inertia
        // que recargue la página con la URL del HOME
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

        // Se indica que se recargue la página (en este caso, la raíz)
        return Inertia::location('login');
    }
}
