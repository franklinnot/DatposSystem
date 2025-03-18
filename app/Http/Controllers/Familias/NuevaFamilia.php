<?php

namespace App\Http\Controllers\Familias;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NuevaFamilia extends Controller
{
    //
    public const COMPONENTE = "Familias/NuevaFamilia";
    public const RUTA = "families/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }
}
