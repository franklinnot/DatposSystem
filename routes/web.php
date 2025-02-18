<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function (Request $request) {
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
})->middleware(['auth', 'no.cache']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'no.cache'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
