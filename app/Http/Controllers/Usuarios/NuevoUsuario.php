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
use Illuminate\Support\Arr;
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
        // retornamos solo los almacenes que no son inventario de tienda
        $almacenes = Almacen::get_exclusive_almacenes_by_id_empresa($user->id_empresa);

        // Filtrar roles con estado = 1
        $roles = $this->filtrarEstado($roles);
        $sucursales = $this->filtrarEstado($sucursales);
        $almacenes = $this->filtrarEstado($almacenes);

        // Mapear los datos a la estructura requerida
        $roles_mapeados = array_map(function ($rol) {
            return [
                'id' => $rol->id_rol,
                'name' => $rol->nombre,
            ];
        }, $roles);

        $sucursales_mapeadas = array_map(function ($rol) {
            return [
                'id' => $rol->id_sucursal,
                'name' => $rol->nombre,
            ];
        }, $sucursales);

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
    
    public function filtrarEstado(array $data){
        $data = array_filter($data, function ($item) {
            return $item->estado == '1';
        });
        return $data;
    }

    public function store(Request $request): Response
    {
        $data_usuario = $request->validate([
            'dni' => 'required|digits:8',
            'nombre' => 'required|string|max:128',
            'id_rol' => 'required|integer',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
            'sucursales' => 'nullable|array',
            'almacenes' => 'nullable|array',
        ]);

        // si no tiene nada tanto el array de sucursales como el de almacenes
        if(!$data_usuario['sucursales'] && $data_usuario['almacenes']){
            return $this->error();
        }

        // Convertir DNI a string antes de guardarlo (para que coincida con CHAR(8))
        $data_usuario['dni'] = (string) $data_usuario['dni'];
        // hasheamos la contraseña
        $data_usuario['password'] = Hash::make($data_usuario['password']);

        $user = Auth::user();
        $id_empresa = $user->id_empresa;
        $data_usuario['id_empresa'] = $id_empresa;

        // obtenemos a la empresa
        $empresa = Empresa::get_empresa_by_id($id_empresa);

        // verificamos que aun pueda seguir registrando usuarios
        if ($empresa->usuarios_registrados >= $empresa->cantidad_usuarios) {
            return $this->errorLimitRegister();
        }

        // Verificar que el dni del usuario sea único antes de registrar
        if (Usuario::existencia_usuario_by_dni($data_usuario['dni'], $id_empresa)) {
            return $this->errorSameDni();
        }

        // Verificar que exista cada sucursal
        if (!empty($data_usuario['sucursales'])) {
            foreach ($data_usuario['sucursales'] as $id_sucursal) {
                if (!Sucursal::existencia_sucursal_by_id($id_sucursal, $id_empresa)) {
                    return $this->error();
                }
            }
        }

        // Verificar que exista cada almacen y que no sea de inventario
        if (!empty($data_usuario['almacenes'])) {
            foreach ($data_usuario['almacenes'] as $id_almacen) {
                if (!Almacen::existencia_exclusive_almacen_by_id($id_almacen, $id_empresa)) {
                    return $this->error();
                }
            }
        }

        // verificar que el email del usuario sea único antes de registrar
        if (Usuario::existencia_usuario_by_email($data_usuario['email'])) {
            return $this->errorSameEmail();
        }

        // si el rol no existe
        $rol = Rol::get_rol_by_id($data_usuario['id_rol'], $id_empresa);
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
            'dni' => 'Por favor, intente registrar con otro DNI.',
        ]);
    }

    public function errorSameEmail(): Response
    {
        throw ValidationException::withMessages([
            'email' => 'Por favor, intente registrar con otro correo.',
        ]);
    }

    public function errorLimitRegister(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'Ha llegado al límite de usuarios por registrar.',
            ]
        ]);
    }

    public function error(): Response
    {
        return Inertia::render(self::COMPONENTE, [
            'toast' => [
                'type' => 'error',
                'message' => 'No fue posible registrar al nuevo usuario.',
            ]
        ]);
    }

}
