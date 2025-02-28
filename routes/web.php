<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('guest', 'no.cache')->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});


Route::middleware(['auth', 'no.cache'])->group(
    function () {
        
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

        // Perfil de Usuario
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile/edit');
        Route::delete('/profile/edit/password', [ProfileController::class, 'destroy'])->name('profile/edit/password');

        #region Sucursales

        // vista para listar sucursales
        Route::get('/stores', function () {
            return Inertia::render('Dashboard');
        })->name('stores');
        


        // vista para crear una nueva sucursal
        Route::get('/stores/new', function () {
            return Inertia::render('Dashboard');
        })->name('stores/new');

        // metodo para crear una sucursal
        Route::post('/stores/new', function () {
            return Inertia::render('Dashboard');
        });



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
    }
);

require __DIR__ . '/auth.php';
