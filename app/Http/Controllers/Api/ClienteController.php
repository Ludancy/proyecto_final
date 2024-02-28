<?php

// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cliente;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());
        return response()->json($cliente, 201);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }
        $cliente->update($request->all());
        return response()->json($cliente, 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }
        $cliente->delete();
        return response()->json(['message' => 'Cliente eliminado con éxito.'], 200);
    }
}
