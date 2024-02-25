<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request) {
        //validación de los datos
        $request->validate([
            'name' => 'required',
            'email_user' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);    
        //alta del usuario
        $user = new User();
        $user->name = $request->name;
        $user->email_user = $request->email_user;
        $user->password = Hash::make($request->password);
        $user->save();
        //respuesta
        /* return response()->json([
            "message" => "Alta exitosa"
        ]); */
        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email_user' => ['required', 'email'],
            'password' => ['required']
        ]);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
    
            // Additional data
            $response = [
                "email_user" => $user->email_user,
                "distid" => "RRA555", // Replace with actual data
                "usuario" => 25, // Replace with actual data
                "token" => $token
            ];
    
            return response($response, Response::HTTP_OK);
        } else {
            return response(["message" => "Credenciales inválidas"], Response::HTTP_UNAUTHORIZED);
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
