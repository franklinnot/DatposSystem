<?php

namespace App\Http\Controllers\Sucursales;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Empresa;
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
            'codigo' => 'required|string|max:128',
            'departamento' => 'required|string|max:128',
            'ciudad' => 'required|string|max:128',
            'direccion' => 'required|string|max:128',
            'telefono' => 'nullable|string|max:128',
            'id_empresa' => 'required|integer',
        ]);

        // verificar que el código de la sucursal sea único antes de registrar
        if (Sucursal::existencia_sucursal_by_codigo($data_sucursal["codigo"], $data_sucursal["id_empresa"])) {
            $this->error_samecode();
        }

        // Datos para registrar el nuevo almacen de stock de tienda
        $data_almacen = array_merge([], $data_sucursal); // una copia del data_sucursal
        $data_almacen["nombre"] = "Inventario de {$data_sucursal['nombre']}";
        $data_almacen["codigo"] = "SCR-{$data_sucursal['codigo']}";

        // verificar que el código del almacén sea único antes de registrar
        if (Almacen::existencia_almacen_by_codigo($data_almacen["codigo"], $data_almacen["id_empresa"])) {
            $this->error_samecode();
        }

        $nuevo_almacen = Almacen::registrar($data_almacen);

        // si no se registró correctamente el almacen
        if(!$nuevo_almacen){
            $this->error();
        }

        $data_sucursal["id_almacen"] = $nuevo_almacen->id_almacen;
        $nueva_sucursal = Sucursal::registrar($data_sucursal);

        // si no se registró correctamente la sucursal
        if(!$nueva_sucursal){
            // eliminamos el almacen que se registro
            Almacen::eliminar_almacen_by_id($nuevo_almacen->id_almacen);
            $this->error();
        }

        // verificamos si debe recargar la pagina cuando ya no pueda registrar mas almacenes
        $refresh = false;
        $empresa = Empresa::get_empresa_by_id($data_sucursal["id_empresa"]);
        if ($empresa->sucursales_registradas === $empresa->cantidad_sucursales) {
            $refresh = true;
        }

        // Guardar mensaje flash en la sesión
        return Inertia::render('Sucursales/NuevaSucursal', [
            'response' => [
                'message' => 'Sucursal y almacén registrados con éxito.',
                'status' => true, // Puedes agregar más claves si es necesario
                'refresh' => $refresh,
            ]
        ]);
    }

    public function error_samecode(){
        throw ValidationException::withMessages([
            'break' => trans('nueva_sucursal.samecode'),
        ]);
    }

    public function error()
    {
        throw ValidationException::withMessages([
            'break' => trans('nueva_sucursal.ups'),
        ]);
    }

}
