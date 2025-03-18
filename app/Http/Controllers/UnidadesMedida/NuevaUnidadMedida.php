<?php

namespace App\Http\Controllers\UnidadesMedida;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\UnidadMedida;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class NuevaUnidadMedida extends Controller
{
    //
    public const COMPONENTE = "UnidadesMedida/NuevaUnidadMedida";
    public const RUTA = "units/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }

    public function store(Request $request): Response
    {
        $data_um = $request->validate([
            'codigo' => 'required|string|max:24',
            'nombre' => 'required|string|max:128',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $data_um['id_empresa'] = $user->id_empresa;

        // verificar que el codigo de la um sea único antes de registrar
        if (UnidadMedida::existencia_unidad_medida_by_codigo($data_um['codigo'], $data_um['id_empresa'])) {
            return $this->errorSameCode();
        }

        // registramos 
        $nueva_um = UnidadMedida::registrar($data_um);

        // si no se registró correctamente la caja
        if (!$nueva_um) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Unidad de medida registrada exitosamente!',
            ],
        ]);
    }

    public function errorSameCode(): Response
    {
        throw ValidationException::withMessages([
            'dni' => 'Por favor, intente registrar con otro código.',
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar la nueva unidad de medida.',
            ]
        ]);
    }

}
