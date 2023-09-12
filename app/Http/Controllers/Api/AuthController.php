<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\RolesUsuarios;
use App\Models\Usuarios;

use App\Http\Controllers\Controller;

class AuthController extends Controller {

    private $jwt;

    function __construct() {
        $this->jwt = new CheckAuth();
    }

    public function getRole() {
        return RolesUsuarios::select('rous_codigo', 'rous_nombre')->orderBy('rous_codigo', 'asc')->get();
    }

    public function logIn(Request $request) {

        $request->validate(
            [
                'run' => 'required|regex:/(^[0-9]{7,8}-[0-9kK]{1}$)/i',
                'clave' => 'required',
                'rol' => 'required',
            ],
            [
                'run.required' => 'El RUN es requerido.',
                'run.regex' => 'El formato del RUN debe ser 12345678-9.',
                'clave.required' => 'La contraseña es requerida.',
                'rol.required' => 'El rol de usuario es requerido.'
            ]
        );

        $usuario = Usuarios::where(['usua_rut' => $request->run, 'rous_codigo' => $request->rol])->first();
        $rol = RolesUsuarios::select('rous_nombre')->where('rous_codigo', $request->rol)->first()->rous_nombre;
        if (!$usuario) return response(['message' => 'El usuario no tiene acceso al sistema con el rol de '.$rol.'.'], 404);
        if ($usuario->usua_vigente == 'N') return response(['message' => 'El usuario no se encuentra habilitado para ingresar al sistema. Por favor verifique si su rol es el correcto, de lo contrario, notifique al administrador.'], 404);

        $validarClave = Hash::check($request->clave, $usuario->usua_clave);
        if (!$validarClave) return response(["message" => "La contraseña es incorrecta."],403);

        // Generar JSON Web Token del usuario y enviarlo al cliente
        return response(["token" => $this->jwt->createToken($usuario->usua_rut, $usuario->rous_codigo), "run" => $usuario->usua_rut, "role" => $usuario->rous_codigo], 200);

    }
}