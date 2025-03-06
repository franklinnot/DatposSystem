<?php

namespace App\Http\Controllers\Sucursales;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Sucursal;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class NuevaSucursal extends Controller
{
    //
    public function show(): Response
    {
        return Inertia::render('Sucursales/NuevaSucursal');
    }

    public function store(Request $request): Response
    {
        $data_sucursal = $request->validate([
            'nombre' => 'required|string|max:128',
            'codigo' => 'required|string|max:128|unique:sucursal,codigo',
            'departamento' => 'required|string|max:128',
            'ciudad' => 'required|string|max:128',
            'direccion' => 'required|string|max:128',
            'telefono' => 'nullable|string|max:128',
            'id_empresa' => 'required|integer|exists:empresa,id_empresa',
        ]);

        // Datos para registrar el nuevo almacen de stock de tienda
        $data_almacen = array_merge([], $data_sucursal);
        $data_almacen["nombre"] = "Inventario de {$data_sucursal['nombre']}";
        $data_almacen["codigo"] = "SCR-{$data_sucursal['codigo']}";

        // verificar que el código del almacén sea único antes de registrar
        if (Almacen::where('codigo', $data_almacen["codigo"])->exists()) {
            throw ValidationException::withMessages([
                'break' => trans('auth.insert_almacen'),
            ]);
        }

        $nuevo_almacen = Almacen::registrar($data_almacen);

        if(!$nuevo_almacen){
            throw ValidationException::withMessages([
                'break' => trans('auth.insert_almacen'),
            ]);
        }

        $data_sucursal["id_almacen"] = $nuevo_almacen->id_almacen;
        $nueva_sucursal = Sucursal::registrar($data_sucursal);

        if(!$nueva_sucursal->id_sucursal){
            throw ValidationException::withMessages([
                'break' => trans('auth.insert_sucursal'),
            ]);; 
        }

        // Guardar mensaje flash en la sesión
        session()->flash('message', 'Sucursal y almacén registrados con éxito.');
        return Inertia::render('Sucursales/NuevaSucursal');
    }




}
