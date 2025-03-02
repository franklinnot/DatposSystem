<?php

namespace App\Http\Controllers\Sucursales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NuevaSucursal extends Controller
{
    //
    public function show(): Response
    {
        return Inertia::render('Sucursales/NuevaSucursal');
    }
}
