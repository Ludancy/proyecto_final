<?php

// app/Http/Controllers/Api/LugarController.php
namespace App\Http\Controllers\Api;

use App\Models\Lugar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class LugarController extends Controller
{

    public function index()
    {
        $lugares = DB::table('lugares')->get();

        return response()->json($lugares, 200);
    }

    public function show($id)
    {
        $lugar = DB::table('lugares')->find($id);

        if (!$lugar) {
            return response()->json(['message' => 'Lugar no encontrado.'], 404);
        }

        return response()->json($lugar, 200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'valor_numerico' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $lugarId = DB::table('lugares')->insertGetId([
                'nombre' => $request->input('nombre'),
                'valor_numerico' => $request->input('valor_numerico'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $lugar = DB::table('lugares')->find($lugarId);

            return response()->json($lugar, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'valor_numerico' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $lugar = DB::table('lugares')->find($id);

        if (!$lugar) {
            return response()->json(['message' => 'Lugar no encontrado.'], 404);
        }

        DB::table('lugares')->where('id', $id)->update([
            'nombre' => $request->input('nombre'),
            'valor_numerico' => $request->input('valor_numerico'),
            'updated_at' => now(),
        ]);

        $lugar = DB::table('lugares')->find($id);

        return response()->json($lugar, 200);
    }

    public function destroy($id)
    {
        $lugar = DB::table('lugares')->find($id);

        if (!$lugar) {
            return response()->json(['message' => 'Lugar no encontrado.'], 404);
        }

        DB::table('lugares')->where('id', $id)->delete();

        return response()->json(['message' => 'Lugar eliminado correctamente.'], 200);
    }
    public function calcularCostoTraslado($idOrigen, $idDestino)
    {
        // Obtener los datos de los lugares
        $lugarOrigen = DB::table('lugares')->find($idOrigen);
        $lugarDestino = DB::table('lugares')->find($idDestino);

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

