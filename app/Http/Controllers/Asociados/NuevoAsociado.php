<?php

namespace App\Http\Controllers\Asociados;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Asociado;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NuevoAsociado extends Controller
{
    //
    public const COMPONENTE = "Asociados/NuevoAsociado";
    public const RUTA = "partners/new";

    public function show(): Response
    {
        return Inertia::render(self::COMPONENTE);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_asociado' => 'required|integer',
            'ruc' => 'nullable|digits:11',
            'dni' => 'nullable|digits:8',
            'email' => 'nullable|string|email|max:255',
            'telefono' => 'nullable|string|max:32',
        ]);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data['id_empresa'] = $id_empresa;

        $tipos = [1, 2];
        if (!in_array($data['tipo_asociado'], $tipos)) {
            return $this->error();
        }

        if(!$data['ruc'] && !$data['dni']){
            return $this->error();
        }

        $tipo = $data['tipo_asociado'];
        if($data['ruc']){
            $ruc = $data['ruc'];
            $asociado = Asociado::where('ruc', $ruc)->where('tipo_asociado', $tipo)->where('id_empresa', $id_empresa)->first();
            if ($asociado) {
                return $this->errorSameRuc();
            }
        }

        if ($data['dni']) {
            $dni = $data['dni'];
            $asociado = Asociado::where('dni', $dni)->where('tipo_asociado', $tipo)->where('id_empresa', $id_empresa)->first();
            if ($asociado) {
                return $this->errorSameDni();
            }
        }

        $asociado = Asociado::registrar($data);
        if (!$asociado) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Asociado registrado exitosamente!',
            ],
        ]);
    }

    public function errorSameRuc(): Response
    {
        throw ValidationException::withMessages([
            'ruc' => 'Por favor, intente registrar con otro RUC.',
        ]);
    }

    public function errorSameDni(): Response
    {
        throw ValidationException::withMessages([
            'dni' => 'Por favor, intente registrar con otro DNI.',
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar su nuevo asociado.',
            ]
        ]);
    }
}
