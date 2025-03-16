<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class NuevoUsuario extends Controller
{
    //
    public const COMPONENTE = "Usuarios/NuevoUsuario";
    public const RUTA = "stores/new";

    public function show(): Response
    {
        $user = Auth::user();
        $roles = Rol::get_roles_by_id_empresa($user->id_empresa);
        $sucursales = Sucursal::get_sucursales_by_id_empresa($user->id_empresa);
        $almacenes = Almacen::get_almacenes_by_id_empresa($user->id_empresa);

        // Filtrar roles con estado = 1
        $roles = array_filter($roles, function ($rol) {
            return $rol->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $roles_mapeados = array_map(function ($rol) {
            return [
                'id' => $rol->id_rol,
                'name' => $rol->nombre,
            ];
        }, $roles);


        // Filtrar sucursales con estado = 1
        $sucursales = array_filter($sucursales, function ($sucursal) {
            return $sucursal->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $sucursales_mapeadas = array_map(function ($rol) {
            return [
                'id' => $rol->id_rol,
                'name' => $rol->nombre,
            ];
        }, $sucursales);


        // Filtrar sucursales con estado = 1
        $sucursales = array_filter($sucursales, function ($sucursal) {
            return $sucursal->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $sucursales_mapeadas = array_map(function ($rol) {
            return [
                'id' => $rol->id_sucursal,
                'name' => $rol->nombre,
            ];
        }, $sucursales);



        // Filtrar almacenes con estado = 1
        $almacenes = array_filter($almacenes, function ($almacen) {
            return $almacen->estado == '1';
        });

        // Mapear los datos a la estructura requerida
        $almacenes_mapeadas = array_map(function ($almacen) {
            return [
                'id' => $almacen->id_almacen,
                'name' => $almacen->nombre,
            ];
        }, $almacenes);

        return Inertia::render(self::COMPONENTE, [
            'roles' => $roles_mapeados,
            'sucursales' => $sucursales_mapeadas,
            'almacenes' => $almacenes_mapeadas,
        ]);
    }

    public function store(Request $request): Response
    {
        $data_usuario = $request->validate([
            'dni' => 'required|digits:8',
            'nombre' => 'required|string|max:128',
            'id_rol' => 'required|integer',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Convertir DNI a string antes de guardarlo (para que coincida con CHAR(8))
        $data_usuario['dni'] = (string) $data_usuario['dni'];
        // hasheamos la contraseña
        $data_usuario['password'] = Hash::make($data_usuario['password']);

        $user = Auth::user();
        $data_usuario['id_empresa'] = $user->id_empresa;

        // obtenemos a la empresa
        $empresa = Empresa::get_empresa_by_id($user->id_empresa);

        // verificamos que aun pueda seguir registrando usuarios
        if ($empresa->usuarios_registrados >= $empresa->cantidad_usuarios) {
            return $this->errorLimitRegister();
        }


        // verificar que el dni del usuario sea único antes de registrar
        if (Usuario::existencia_usuario_by_dni($data_usuario['dni'], $data_usuario['id_empresa'])) {
            return $this->errorSameDni();
        }

        // verificar que el email del usuario sea único antes de registrar
        if (Usuario::existencia_usuario_by_email($data_usuario['email'])) {
            return $this->errorSameEmail();
        }

        // si el no existe
        $rol = Rol::get_rol_by_id($data_usuario['id_rol'], $data_usuario['id_empresa']);
        if (!$rol) {
            return $this->error();
        }

        // si el rol no está activo
        if ($rol->estado != '1') {
            return $this->error();
        }

        // registramos el nuvo usuario
        $nuevo_usuario = Usuario::registrar($data_usuario);

        // si no se registró correctamente la caja
        if (!$nuevo_usuario) {
            return $this->error();
        }

        // Guardar mensaje flash en la sesión y enviar datos actualizados al cliente
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'success',
                'message' => 'Usuario registrado exitosamente!',
            ],
        ]);
    }

    public function errorSameDni(): Response
    {
        throw ValidationException::withMessages([
            'dni' => trans('usuarios.samedni'),
        ]);
    }

    public function errorSameEmail(): Response
    {
        throw ValidationException::withMessages([
            'email' => trans('usuarios.sameemail'),
        ]);
    }

    public function errorLimitRegister(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('usuarios.limit_registers'),
            ]
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => trans('usuarios.error'),
            ]
        ]);
    }

}
