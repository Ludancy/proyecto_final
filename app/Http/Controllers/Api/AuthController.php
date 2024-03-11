<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\PersonalAdmin;
use App\Models\Chofer;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|email|unique:auths',
                'password' => 'required|confirmed',
                'idRol' => 'required',
                // Añade aquí las validaciones necesarias para los campos específicos de cada rol
            ]);
    
            // Crear el registro en la tabla 'auths'
            $authId = DB::table('auths')->insertGetId([
                'correo' => $request->correo,
                'password' => Hash::make($request->password),
                'idRol' => $request->idRol,
                'fechaCreacion' => now(),
                'estado' => 'activo',
            ]);
    
            // Obtener los datos del registro creado
            $authData = DB::table('auths')->where('id', $authId)->first();
    
            // Según el rol, insertar en la tabla específica (chofer, cliente, personalAdmin)
            switch ($request->idRol) {
                case 1: // Personal Admin
                    $request->validate([
                        'nombre' => 'required',
                        'apellido' => 'required',
                        'cedula' => 'required',
                        'fechaNacimiento' => 'required',
                        // Agrega aquí las validaciones necesarias para los campos específicos de personalAdmin
                    ]);
    
                    DB::table('personaladmin')->insert([
                        'idAuth' => $authId,
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'cedula' => $request->cedula,
                        'fechaNacimiento' => $request->fechaNacimiento,
                        // Agrega aquí los campos específicos para el personal admin
                    ]);
                    break;
                case 2: // Cliente
                    $request->validate([
                        'nombre' => 'required',
                        'apellido' => 'required',
                        'cedula' => 'required',
                        'fechaNacimiento' => 'required',
                        // Agrega aquí las validaciones necesarias para los campos específicos de cliente
                    ]);
    
                    DB::table('cliente')->insert([
                        'idAuth' => $authId,
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'cedula' => $request->cedula,
                        'fechaNacimiento' => $request->fechaNacimiento,
                        // Agrega aquí los campos específicos para el cliente
                    ]);
                    break;
                case 3: // Chofer
                    $request->validate([
                        'nombre' => 'required',
                        'apellido' => 'required',
                        'cedula' => 'required',
                        'fechaNacimiento' => 'required',
                        // Agrega aquí las validaciones necesarias para los campos específicos de chofer
                    ]);
    
                    DB::table('chofers')->insert([
                        'idAuth' => $authId,
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'cedula' => $request->cedula,
                        'fechaNacimiento' => $request->fechaNacimiento,
                        // Agrega aquí los campos específicos para el chofer
                    ]);
                    break;
                // Añade más casos según sea necesario
            }
    
            // Obtener los datos completos del registro, incluyendo las fechas y el ID
            $authData = DB::table('auths')->where('id', $authId)->first();
    
            return response()->json($authData, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email_user' => ['required', 'email'],
                'password' => ['required']
            ]);
    
            // Cambia 'email_user' a 'correo' para que coincida con la columna en la base de datos
            $credentials['correo'] = $credentials['email_user'];
            unset($credentials['email_user']);
    
            $user = DB::table('auths')
                ->where('correo', $credentials['correo'])
                ->first();
    
            if ($user && Hash::check($credentials['password'], $user->password)) {
                // Obtener datos adicionales según el tipo de usuario
                $additionalData = $this->getUserAdditionalData($user);
                $nombreRol = DB::table('roles')
                ->where('id', $user->idRol)
                ->value('nombre');
                // Generar token
                $token = $this->generateToken($user);
    
                // Response data
                $response = [
                    "email_user" => $user->correo,
                    "distid" => "RRA555", // Reemplaza con datos reales
                    "role" => $user->idRol,
                    "role_nombre" => $nombreRol,
                    "token" => $token,
                    "additional_data" => $additionalData,
                ];
    
                return response($response, Response::HTTP_OK);
            } else {
                return response(["message" => "Credenciales inválidas"], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    private function generateToken($user)
    {
        $token = bin2hex(random_bytes(40));
        
        DB::table('auth_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return $token;
    }
    
    private function getUserAdditionalData($user)
    {
        // Agrega lógica para obtener datos adicionales según el tipo de usuario
        switch ($user->idRol) {
            case 1: // Personal Admin
                // Obtener datos adicionales de personal admin
                $additionalData = DB::table('personaladmin')->where('idAuth', $user->id)->first();
                break;
            case 2: // Cliente
                // Obtener datos adicionales de cliente
                $additionalData = DB::table('cliente')->where('idAuth', $user->id)->first();
                break;
            case 3: // Chofer
                // Obtener datos adicionales de chofer
                $additionalData = DB::table('chofers')->where('idAuth', $user->id)->first();
                break;
            // Agrega más casos según sea necesario
            default:
                $additionalData = null;
        }
    
        return $additionalData;
    }
    
    
    public function renewToken(Request $request)
    {
        try {
            // Obtén el usuario del token actual
            $user = $request->attributes->get('auth_user');
    
            // Genera un nuevo token
            $newToken = $this->generateToken($user);
    
            // Obtén datos adicionales según el tipo de usuario
            $additionalData = $this->getUserAdditionalData($user);
    
            // Verifica si $additionalData es nulo antes de acceder a sus propiedades
            if ($additionalData) {
                // Construye la respuesta con el nuevo token y la información del usuario
                $response = [
                    "email_user" => $user->correo,
                    "rol" => $user->idRol,
                    "distid" => "RRA555", // Reemplaza con datos reales
                    "nombre" => $additionalData->nombre,
                    "apellido" => $additionalData->apellido,
                    "cedula" => $additionalData->cedula,
                    "idUsuario" => $additionalData->id,
                    "idAuth" => $additionalData->idAuth,
                    "token" => $newToken,
                ];
            } else {
                // Manejar el caso en que $additionalData sea nulo
                return response(["error" => "Datos adicionales del usuario no encontrados"], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function userProfile(Request $request)
    {
        try {
            $user = $request->attributes->get('auth_user');
    
            // Agrega instrucciones de depuración
            \Log::info('User in userProfile:', ['user' => $user]);
    
            // Resto de tu lógica para el perfil del usuario
            // ...
    
            return response(['message' => 'userProfile OK', 'userData' => $user], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(['error' => $e->getMessage()], 500);
        }
    }
    
    public function logout() {
        $cookie = Cookie::forget('cookie_token');
        return response(["message"=>"Cierre de sesión OK"], Response::HTTP_OK)->withCookie($cookie);
    }

    public function allUsers() {
       $users = User::all();
       return response()->json([
        "users" => $users
       ]);
    }
}
