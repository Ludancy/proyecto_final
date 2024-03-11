<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckAuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
    
        if (!$token || !DB::table('auth_tokens')->where('token', $token)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Obtener el usuario asociado con el token y asignarlo a la solicitud
        $user = DB::table('auths')->where('id', function($query) use ($token) {
            $query->select('user_id')->from('auth_tokens')->where('token', $token);
        })->first();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Asignar el usuario autenticado a la solicitud
        $request->attributes->add(['auth_user' => $user]);
    
        return $next($request);
    }
}
