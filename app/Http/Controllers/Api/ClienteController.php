<?php

// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Traslado;

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
        $cliente->user()->delete();
        return response()->json(['message' => 'Cliente eliminado con éxito.'], 200);
    }

    public function solicitarTraslado(Request $request, $idCliente)
    {
        // Validar la solicitud
        $request->validate([
            'origen' => 'required',
            'destino' => 'required',
            'costo' => 'required|numeric',
            'idChofer' => 'required|exists:chofers,id', // Validar que el idChofer exista en la tabla choferes
        ]);
    
        // Buscar al cliente por ID
        $cliente = Cliente::find($idCliente);
    
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }
    
        $traslado = new Traslado([
            'origen' => $request->origen,
            'destino' => $request->destino,
            'costo' => $request->costo,
            'estado' => 'Pendiente', // Estado inicial del traslado
            'idChofer' => $request->idChofer, // Asegúrate de proporcionar el idChofer
        ]);
        
        // Asociar el traslado al cliente
        $cliente->traslados()->save($traslado);
        
        // Asociar el chofer al traslado
        $traslado->chofer()->associate($request->idChofer)->save();
        
        return response()->json($traslado, 201);
    }

    public function recargaSaldo(Request $request, $idCliente)
    {
        // Validar la solicitud
        $request->validate([
            'fechaRecarga' => 'required|date',
            'referencia' => 'required',
            'idBanco' => 'required|exists:bancos,id',
            'monto' => 'required|numeric',
        ]);

        // Buscar al cliente por ID
        $cliente = Cliente::find($idCliente);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        // Buscar el banco por ID
        $banco = Banco::find($request->idBanco);

        if (!$banco) {
            return response()->json(['message' => 'Banco no encontrado.'], 404);
        }

        // Registrar la recarga de saldo
        $recarga = new SaldoCliente([
            'fecha_recarga' => $request->fechaRecarga,
            'referencia' => $request->referencia,
            'monto' => $request->monto,
        ]);

        // Asociar la recarga al cliente y al banco
        $cliente->saldoRecargas()->save($recarga);
        $recarga->banco()->associate($banco)->save();

        // Actualizar el saldo del cliente
        $cliente->saldo += $request->monto;
        $cliente->save();

        return response()->json(['message' => 'Recarga de saldo realizada con éxito.']);
    }
    

}
