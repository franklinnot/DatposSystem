<?php

namespace App\Http\Controllers\TiposOperacion;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\TipoOperacion;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class NuevoTipoOperacion extends Controller
{
    //
    public const COMPONENTE = "TiposOperacion/NuevoTipoOperacion";
    public const RUTA = "operationtypes/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }

    public function store(Request $request): Response
    {
        $data_tipoOperacion = $request->validate([
            'nombre' => 'required|string|max:128',
            'serie' => 'required|string|max:4',
            'tipo_movimiento' => 'required|integer',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_tipoOperacion['id_empresa'] = $id_empresa;
        $data_tipoOperacion['serie'] = strtoupper($data_tipoOperacion['serie']);

        // verificar que el código de la caja sea único antes de registrar
        if (TipoOperacion::existencia_tipo_operacion_by_serie($data_tipoOperacion['serie'], $id_empresa)) {
            return $this->errorSameSerie();
        }

        $movimientos = [1, 2 ,3]; // 1: entrada, 2: salida, 3: traslado
        if(!in_array($data_tipoOperacion['tipo_movimiento'], $movimientos)) {
            return $this->error();
        }

        // registramos 
        $nuevo_tipoOperacion = TipoOperacion::registrar($data_tipoOperacion);

        // si no se registró correctamente
        if (!$nuevo_tipoOperacion) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Tipo de operación registrado exitosamente!',
            ],
        ]);
    }

    public function errorSameSerie(): Response
    {
        throw ValidationException::withMessages([
            'codigo' => 'Por favor, intente registrar con otra serie.',
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar su nuevo tipo de operación.',
            ]
        ]);
    }
}
