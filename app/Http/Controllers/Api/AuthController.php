<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\PersonalAdmin;
use App\Models\Chofer;

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
            $auth = User::create([
                'correo' => $request->correo,
                'password' => Hash::make($request->password),
                'idRol' => $request->idRol,
                'fechaCreacion' => now(),
                'estado' => 'activo',
            ]);
    
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
            
                    PersonalAdmin::create([
                        'idAuth' => $auth->id,
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
            
                    Cliente::create([
                        'idAuth' => $auth->id,
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
            
                    Chofer::create([
                        'idAuth' => $auth->id,
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'cedula' => $request->cedula,
                        'fechaNacimiento' => $request->fechaNacimiento,
                        // Agrega aquí los campos específicos para el chofer
                    ]);
                    break;
                // Añade más casos según sea necesario
            }
    
            return response($auth, Response::HTTP_CREATED);
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

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('token')->plainTextToken;

                // Obtener datos adicionales según el tipo de usuario
                $additionalData = $this->getUserAdditionalData($user);

                // Response data
                $response = [
                    "email_user" => $user->correo,
                    "distid" => "RRA555", // Reemplaza con datos reales
                    "role" => $user->idRol,
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

    private function getUserAdditionalData($user)
    {
        switch ($user->idRol) {
            case 1: // Personal Admin
                $personalAdmin = PersonalAdmin::where('idAuth', $user->id)->first();
                return [
                    'nombre' => $personalAdmin->nombre,
                    'apellido' => $personalAdmin->apellido,
                    'cedula' => $personalAdmin->cedula,
                    'fechaNacimiento' => $personalAdmin->fechaNacimiento,
                    // Agrega aquí otros campos específicos para el personal admin
                ];
            case 2: // Cliente
                $cliente = Cliente::where('idAuth', $user->id)->first();
                return [
                    'nombre' => $cliente->nombre,
                    'apellido' => $cliente->apellido,
                    'cedula' => $cliente->cedula,
                    'fechaNacimiento' => $cliente->fechaNacimiento,
                    // Agrega aquí otros campos específicos para el cliente
                ];
            case 3: // Chofer
                $chofer = Chofer::where('idAuth', $user->id)->first();
                return [
                    'nombre' => $chofer->nombre,
                    'apellido' => $chofer->apellido,
                    'cedula' => $chofer->cedula,
                    'fechaNacimiento' => $chofer->fechaNacimiento,
                    // Agrega aquí otros campos específicos para el chofer
                ];
            // Agrega más casos según sea necesario para otros roles
            default:
                return [];
        }
    }
    
    
    

    public function userProfile(Request $request) {
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user()
        ], Response::HTTP_OK);
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
