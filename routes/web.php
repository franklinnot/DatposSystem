<?php

use App\Http\Controllers\Almacenes\NuevoAlmacen;
use App\Http\Controllers\Asociados\NuevoAsociado;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cajas\NuevaCaja;
use App\Http\Controllers\Familias\NuevaFamilia;
use App\Http\Controllers\Operaciones\NuevaOperacion;
use App\Http\Controllers\Productos\NuevoProducto;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles\NuevoRol;
use App\Http\Controllers\Sucursales\NuevaSucursal;
use App\Http\Controllers\TiposOperacion\NuevoTipoOperacion;
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
        Route::post('/stores/new', [NuevaSucursal::class, 'store']);


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
        Route::post('/cashregisters/new', [NuevaCaja::class, 'store']);


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
        Route::post('/warehouses/new', [NuevoAlmacen::class, 'store']);


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
        Route::post('/roles/new', [NuevoRol::class, 'store']);


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
        Route::post('/users/new', [NuevoUsuario::class, 'store']);


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
        Route::post('/units/new', [NuevaUnidadMedida::class, 'store']);


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

        #region Familias de productos

        // vista para listar
        Route::get('/families', function () {
            return Inertia::render('Dashboard');
        })->name('families');


        // vista para registrar
        Route::get('/families/new', [NuevaFamilia::class, 'show'])
            ->name('families/new');

        // metodo para registrar
        Route::post('/families/new', [NuevaFamilia::class, 'store']);


        // vista para editar
        Route::get('/families/edit', function () {
            return Inertia::render('Dashboard');
        })->name('families/edit');

        // metodo para editar 
        Route::patch('/families/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/families/delete', function () {
            return Inertia::render('Dashboard');
        })->name('families/delete');

        #endregion

        #region Productos

        // vista para listar
        Route::get('/products', function () {
            return Inertia::render('Dashboard');
        })->name('products');


        // vista para registrar
        Route::get('/products/new', [NuevoProducto::class, 'show'])
            ->name('products/new');

        // metodo para registrar
        Route::post('/products/new', [NuevoProducto::class, 'store']);


        // vista para editar
        Route::get('/products/edit', function () {
            return Inertia::render('Dashboard');
        })->name('products/edit');

        // metodo para editar 
        Route::patch('/products/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/products/delete', function () {
            return Inertia::render('Dashboard');
        })->name('products/delete');

        #endregion

        #region Asociados

        // vista para listar
        Route::get('/partners', function () {
            return Inertia::render('Dashboard');
        })->name('partners');


        // vista para registrar
        Route::get('/partners/new', [NuevoAsociado::class, 'show'])
            ->name('partners/new');

        // metodo para registrar
        Route::post('/partners/new', [NuevoAsociado::class, 'store']);


        // vista para editar
        Route::get('/partners/edit', function () {
            return Inertia::render('Dashboard');
        })->name('partners/edit');

        // metodo para editar 
        Route::patch('/partners/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/partners/delete', function () {
            return Inertia::render('Dashboard');
        })->name('partners/delete');

        #endregion

        #region Tipos de Operacion

        // vista para listar
        Route::get('/operationtypes', function () {
            return Inertia::render('Dashboard');
        })->name('operationtypes');


        // vista para registrar
        Route::get('/operationtypes/new', [NuevoTipoOperacion::class, 'show'])
            ->name('operationtypes/new');

        // metodo para registrar
        Route::post('/operationtypes/new', [NuevoTipoOperacion::class, 'store']);


        // vista para editar
        Route::get('/operationtypes/edit', function () {
            return Inertia::render('Dashboard');
        })->name('operationtypes/edit');

        // metodo para editar 
        Route::patch('/operationtypes/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/operationtypes/delete', function () {
            return Inertia::render('Dashboard');
        })->name('operationtypes/delete');

        #endregion

        #region Operaciones

        // vista para listar
        Route::get('/operations', function () {
            return Inertia::render('Dashboard');
        })->name('operations');


        // vista para registrar
        Route::get('/operations/new', [NuevaOperacion::class, 'show'])
            ->name('operations/new');

        // metodo para registrar
        Route::post('/operations/new', [NuevaOperacion::class, 'store']);


        // vista para editar
        Route::get('/operations/edit', function () {
            return Inertia::render('Dashboard');
        })->name('operations/edit');

        // metodo para editar 
        Route::patch('/operations/edit', function () {
            return Inertia::render('Dashboard');
        });


        // metodo para eliminar
        Route::post('/operations/delete', function () {
            return Inertia::render('Dashboard');
        })->name('operations/delete');

        #endregion


    }
);

require __DIR__ . '/auth.php';
