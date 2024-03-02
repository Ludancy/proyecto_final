<?php

// app/Http/Controllers/Api/LugarController.php
namespace App\Http\Controllers\Api;

use App\Models\Lugar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::all();
        return response()->json($lugares, 200);
    }

    public function show(Lugar $lugar)
    {
        return response()->json($lugar, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'valor_numerico' => 'required|numeric'
            ]);

            $lugar = Lugar::create($request->all());

            return response()->json($lugar, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function update(Request $request, Lugar $lugar)
    {
        $request->validate([
            'nombre' => 'required',
            'valor_numerico' => 'required|numeric',
        ]);

        $lugar->update($request->all());

        return response()->json($lugar, 200);
    }

    public function destroy(Lugar $lugar)
    {
        $lugar->delete();

        return response()->json(['message' => 'Lugar eliminado correctamente.'], 200);
    }

    public function calcularCostoTraslado($idOrigen, $idDestino)
    {
        // Obtener los datos de los lugares
        $lugarOrigen = Lugar::find($idOrigen);
        $lugarDestino = Lugar::find($idDestino);

        // Verificar si los lugares existen
        if (!$lugarOrigen || !$lugarDestino) {
            return response()->json(['message' => 'Lugar no encontrado.'], 404);
        }

        // Obtener el valor numérico de los lugares
        $valorNumericoOrigen = $lugarOrigen->valor_numerico;
        $valorNumericoDestino = $lugarDestino->valor_numerico;

        // Calcular el costo del traslado
        $costoTraslado = abs($valorNumericoDestino - $valorNumericoOrigen);

        // Devolver los datos de los lugares y el costo del traslado
        return response()->json([
            'lugar_origen' => $lugarOrigen,
            'lugar_destino' => $lugarDestino,
            'costo_traslado' => $costoTraslado
        ]);
    }
}

