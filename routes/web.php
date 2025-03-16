<?php

use App\Http\Controllers\Almacenes\NuevoAlmacen;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cajas\NuevaCaja;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles\NuevoRol;
use App\Http\Controllers\Sucursales\NuevaSucursal;
use App\Http\Controllers\UnidadesMedida\NuevaUnidadMedida;
use App\Http\Controllers\Usuarios\NuevoUsuario;
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
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
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

        // vista para listar
        Route::get('/stores', function () {
            return Inertia::render('Dashboard');
        })->name('stores');


        // vista para registrar
        Route::get('/stores/new', [NuevaSucursal::class, 'show'])
            ->name('stores/new');

        // metodo para registrar
        Route::post('/stores/new', [NuevaSucursal::class, 'store'])->name('stores/new');


        // vista para editar
        Route::get('/stores/edit', function () {
            return Inertia::render('Dashboard');
        })->name('stores/edit');

        // metodo para editar 
        Route::patch('/stores/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/stores/delete', function () {
            return Inertia::render('Dashboard');
        })->name('stores/delete');

        #endregion

        #region Cajas

        // vista para listar
        Route::get('/cashregisters', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters');


        // vista para registrar
        Route::get('/cashregisters/new', [NuevaCaja::class, 'show'])
            ->name('cashregisters/new');

        // metodo para registrar
        Route::post('/cashregisters/new', [NuevaCaja::class, 'store'])
            ->name('cashregisters/new');


        // vista para editar
        Route::get('/cashregisters/edit', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters/edit');

        // metodo para editar
        Route::patch('/cashregisters/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/cashregisters/delete', function () {
            return Inertia::render('Dashboard');
        })->name('cashregisters/delete');

        #endregion

        #region Almacenes

        // vista para listar
        Route::get('/warehouses', function () {
            return Inertia::render('Dashboard');
        })->name('warehouses');


        // vista para registrar
        Route::get('/warehouses/new', [NuevoAlmacen::class, 'show'])
            ->name('warehouses/new');

        // metodo para registrar
        Route::post('/warehouses/new', [NuevoAlmacen::class, 'store'])
            ->name('warehouses/new');


        // vista para editar
        Route::get('/warehouses/edit', function () {
            return Inertia::render('Dashboard');
        })->name('warehouses/edit');

        // metodo para editar 
        Route::patch('/warehouses/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/warehouses/delete', function () {
            return Inertia::render('Dashboard');
        })->name('warehouses/delete');

        #endregion

        #region Roles

        // vista para listar
        Route::get('/roles', function () {
            return Inertia::render('Dashboard');
        })->name('roles');


        // vista para registrar
        Route::get('/roles/new', [NuevoRol::class, 'show'])
            ->name('roles/new');

        // metodo para registrar
        Route::post('/roles/new', [NuevoRol::class, 'store'])
            ->name('roles/new');


        // vista para editar
        Route::get('/roles/edit', function () {
            return Inertia::render('Dashboard');
        })->name('roles/edit');

        // metodo para editar 
        Route::patch('/roles/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/roles/delete', function () {
            return Inertia::render('Dashboard');
        })->name('roles/delete');

        #endregion

        #region Usuarios

        // vista para listar
        Route::get('/users', function () {
            return Inertia::render('Dashboard');
        })->name('users');


        // vista para registrar
        Route::get('/users/new', [NuevoUsuario::class, 'show'])
            ->name('users/new');

        // metodo para registrar
        Route::post('/users/new', [NuevoUsuario::class, 'store'])
            ->name('users/new');


        // vista para editar
        Route::get('/users/edit', function () {
            return Inertia::render('Dashboard');
        })->name('users/edit');

        // metodo para editar 
        Route::patch('/users/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/users/delete', function () {
            return Inertia::render('Dashboard');
        })->name('users/delete');

        #endregion

        #region Unidades de Medida

        // vista para listar
        Route::get('/units', function () {
            return Inertia::render('Dashboard');
        })->name('units');


        // vista para registrar
        Route::get('/units/new', [NuevaUnidadMedida::class, 'show'])
            ->name('units/new');

        // metodo para registrar
        Route::post('/units/new', [NuevaUnidadMedida::class, 'store'])
            ->name('units/new');


        // vista para editar
        Route::get('/units/edit', function () {
            return Inertia::render('Dashboard');
        })->name('units/edit');

        // metodo para editar 
        Route::patch('/units/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/units/delete', function () {
            return Inertia::render('Dashboard');
        })->name('units/delete');

        #endregion
    }
);

require __DIR__ . '/auth.php';
