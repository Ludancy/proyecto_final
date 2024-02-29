<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\PersonalAdmin;
use App\Models\Chofer;
use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class BancoController extends Controller
{
    // Obtener todos los bancos
    public function index()
    {
        $bancos = Banco::all();
        return response()->json($bancos);
    }

    // Obtener un banco por ID
    public function show($id)
    {
        $banco = Banco::findOrFail($id);
        return response()->json($banco);
    }

    // Crear un nuevo banco
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|string',
            // Agrega otros campos según tus necesidades
        ]);

        $banco = Banco::create($request->all());
        return response()->json($banco, 201);
    }

    // Actualizar un banco por ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|string',
            // Agrega otros campos según tus necesidades
        ]);

        $banco = Banco::findOrFail($id);
        $banco->update($request->all());
        return response()->json($banco, 200);
    }

    // Eliminar un banco por ID
    public function destroy($id)
    {
        $banco = Banco::findOrFail($id);
        $banco->delete();
        return response()->json(null, 204);
    }
}