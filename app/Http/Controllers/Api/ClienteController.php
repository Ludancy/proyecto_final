<?php

// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Traslado;
use App\Models\Banco;
use App\Models\SaldoCliente;
use App\Models\Chofer;
use App\Models\Lugar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        try {
            // Validar la solicitud
            $request->validate([
                'idOrigen' => 'required|exists:lugares,id',
                'idDestino' => 'required|exists:lugares,id',
                'costo' => 'required|numeric',
            ]);
    
            // Buscar al cliente por ID
            $cliente = Cliente::find($idCliente);
    
            if (!$cliente) {
                return response()->json(['message' => 'Cliente no encontrado.'], 404);
            }
    
            // Buscar chofer aleatorio
            $choferAleatorio = Chofer::inRandomOrder()->first();
    
            if (!$choferAleatorio) {
                return response()->json(['message' => 'No hay choferes disponibles.'], 400);
            }
    
            // Obtener el valor numérico de los lugares
            $valorNumericoOrigen = Lugar::where('id', $request->idOrigen)->value('valor_numerico');
            $valorNumericoDestino = Lugar::where('id', $request->idDestino)->value('valor_numerico');
    
            // Calcular el costo del traslado
            $costoTraslado = abs($valorNumericoDestino - $valorNumericoOrigen);
    
            // Crear el traslado
            $traslado = new Traslado([
                'origen' => $request->idOrigen,
                'destino' => $request->idDestino,
                'costo' => $costoTraslado,
                'estado' => 'Pendiente',
                'idChofer' => $choferAleatorio->id,
            ]);
    
            // Asignar el traslado al cliente
            $cliente->traslados()->save($traslado);
    
            // Actualizar saldos
            $cliente->decrement('saldo', $costoTraslado);
            $choferAleatorio->increment('saldo', $costoTraslado * 0.7); // 70% para el chofer
    
            return response()->json($traslado, 201);
    
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(['error' => $e->getMessage()]);
        }
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

        try {
            // Buscar al cliente por ID
            $cliente = Cliente::findOrFail($idCliente);

            // Buscar el banco por ID
            $banco = Banco::findOrFail($request->idBanco);

            // Registrar la recarga de saldo con la asociación al cliente y al banco
            $recarga = new SaldoCliente([
                'fecha_recarga' => $request->fechaRecarga,
                'referencia' => $request->referencia,
                'monto' => $request->monto,
                'idBanco' => $banco->id,
            ]);

            // Asociar la recarga al cliente y al banco
            $cliente->saldoRecargas()->save($recarga);
            $recarga->banco()->associate($banco)->save();

            // Actualizar el saldo del cliente
            $cliente->saldo += $request->monto;
            $cliente->save();

            return response()->json(['message' => 'Recarga de saldo realizada con éxito.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Manejar la excepción de modelo no encontrado
            return response()->json(['error' => 'Cliente o banco no encontrado.'], 404);
        } catch (\Exception $e) {
            // Manejar otras excepciones
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function obtenerTrasladosCliente(Request $request)
    {
        try {
            // Verificar si el usuario autenticado es un cliente
            $user = Auth::user();
    
            if (!$user->cliente) {
                return response()->json(['message' => 'El usuario autenticado no es un cliente.'], 403);
            }

            // Obtener los traslados realizados por el cliente
            $traslados = Traslado::where('idCliente', $user->cliente->id)
                ->orderBy('created_at', 'desc')
                ->get();
    
            // Puedes personalizar la respuesta según tus necesidades
            return response()->json(['traslados' => $traslados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function trasladosCliente($clienteId)
    {
        try {
       
            // Obtener los traslados realizados por el cliente
            $traslados = Traslado::where('idCliente', $clienteId)
                ->orderBy('created_at', 'desc')
                ->get();
    
            // Puedes personalizar la respuesta según tus necesidades
            return response()->json(['traslados' => $traslados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function historialRecargasCliente(Request $request, $clienteId)
    {
        try {
            // Obtener el historial de recargas del cliente
            $historialRecargas = SaldoCliente::where('idCliente', $clienteId)
                ->orderBy('fecha_recarga', 'desc')
                ->get();
    
            // Puedes personalizar la respuesta según tus necesidades
            return response()->json(['historial_recargas' => $historialRecargas]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
