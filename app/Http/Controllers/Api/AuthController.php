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
                case 1: // Cliente
                    Cliente::create([
                        'idAuth' => $auth->id,
                        // Agrega aquí los campos específicos para el cliente
                    ]);
                    break;
                case 2: // Personal Admin
                    PersonalAdmin::create([
                        'idAuth' => $auth->id,
                        // Agrega aquí los campos específicos para el personal admin
                    ]);
                    break;
                case 3: // Chofer
                    Chofer::create([
                        'idAuth' => $auth->id,
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
    public function login(Request $request) {
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
    
                // Additional data
                $response = [
                    "email_user" => $user->correo, // Ahora se utiliza 'correo' en lugar de 'email_user'
                    "distid" => "RRA555", // Reemplaza con datos reales
                    "role" => $user->idRol, // Reemplaza con datos reales
                    "token" => $token
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
