<?php

namespace App\Http\Controllers\Familias;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Familia;
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

    public function store(Request $request): Response
    {
        $data_familia = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:24',
            'color' => 'nullable|hex_color',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_familia['id_empresa'] = $id_empresa;
        $data_familia['codigo'] = strtoupper($data_familia['codigo']);

        // verificar que el código  sea único antes de registrar
        if (Familia::existencia_familia_by_codigo($data_familia['codigo'], $id_empresa)) {
            return $this->errorSameCode();
        }

        // registramos la nueva caja
        $nueva_familia = Familia::registrar($data_familia);

        // si no se registró correctamente la caja
        if (!$nueva_familia) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Familia registrada exitosamente!',
            ],
        ]);
    }

    public function errorSameCode(): Response
    {
        throw ValidationException::withMessages([
            'codigo' => 'Por favor, intente registrar con otro código.',
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar su nueva familia.',
            ]
        ]);
    }


}
