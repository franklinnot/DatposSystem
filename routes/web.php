<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cajas\NuevaCaja;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sucursales\NuevaSucursal;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas globales para usuarios NO autenticados
Route::middleware('guest', 'no.cache')->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Rutas globales para usuarios autenticados
Route::middleware(['auth', 'no.cache'])->group(function () {

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
});

Route::middleware(['auth', 'no.cache', 'verified.access'])->group(
    function () {

        #region Perfil de Usuario -> editar y cambiar contraseña

        Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile/edit');
        Route::delete('/profile/edit/password', [ProfileController::class, 'destroy'])->name('profile/edit/password');

        #endregion

        #region Perfil de Empresa -> ver info y suscripciones y editar info

        Route::get('/business', [ProfileController::class, 'update'])->name('business');
        Route::get('/business/edit', [ProfileController::class, 'destroy'])->name('business/edit');
        Route::get('/business/subscriptions', [ProfileController::class, 'destroy'])->name('/business/subscriptions');

        #endregion

        #region Dashboard

        // ruta raiz que redirige al dashboard
        Route::get('/', function () {
            $dashboardUrl = route('dashboard');

            return response(
                '<!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="utf-8">
                    <title>Dashboard</title>
                    <script type="text/javascript">
                        // Reemplaza la URL actual en el historial por la de dashboard
                        history.replaceState(null, "", "' . $dashboardUrl . '");
                        // Recarga la página para cargar la ruta dashboard
                        window.location.reload();
                    </script>
                </head>
                <body>
                    Dashboard
                </body>
                </html>'
            );
        });

        // vista del dashboard
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        #endregion

        #region Sucursales

        // vista para listar sucursales
        Route::get('/stores', function () {
            return Inertia::render('Dashboard');
        })->name('stores');


        // vista para crear una nueva sucursal
        Route::get('/stores/new', [NuevaSucursal::class, 'show'])
            ->name('stores/new');

        // metodo para crear una sucursal
        Route::post('/stores/new', [NuevaSucursal::class, 'store'])->name('stores/new');


        // vista para editar una sucursal
        Route::get('/stores/edit', function () {
            return Inertia::render('Dashboard');
        })->name('stores/edit');

        // metodo para editar una sucursal
        Route::patch('/stores/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar una sucursal
        Route::post('/stores/delete', function () {
            return Inertia::render('Dashboard');
        })->name('stores/delete');

        #endregion

        #region Cajas

        // vista para listar cajas
        Route::get('/cashregisters', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters');


        // vista para crear una nueva caja
        Route::get('/cashregisters/new', [NuevaCaja::class, 'show'])
            ->name('cashregisters/new');

        // metodo para crear una caja
        Route::post('/cashregisters/new', [NuevaCaja::class, 'store'])
            ->name('cashregisters/new');


        // vista para editar una caja
        Route::get('/cashregisters/edit', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters/edit');

        // metodo para editar una caja
        Route::patch('/cashregisters/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar una caja
        Route::post('/cashregisters/delete', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters/delete');

        #endregion

    }
);

require __DIR__ . '/auth.php';
