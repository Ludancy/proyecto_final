<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;



use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class ChoferController extends Controller
{

    // Agrega esta función al final de tu controlador ChoferController.php
    public function evaluacionPsicologica(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idChofer' => 'required|exists:chofers,id',
            'calificacion' => 'required|numeric|min:0|max:10',
            // Agrega otras reglas de validación según tus necesidades
        ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $pruebaChofer = PruebaChofer::create([
        'idChofer' => $request->idChofer,
        'calificacion' => $request->calificacion,
        // Agrega otros campos según tus necesidades
    ]);

    
    return response()->json($pruebaChofer, 201);
}

    public function index()
    {
        $choferes = Chofer::all();
        return response()->json($choferes, 200);
    }

    public function show($id)
    {
        $chofer = Chofer::find($id);

        if (!$chofer) {
            return response()->json(['message' => 'Chofer no encontrado'], 404);
        }

        return response()->json($chofer, 200);
    }

    public function getInfo($id)
    {
        try {
            // Encuentra al chofer por su ID
            $chofer = Chofer::find($id);

            // Si el chofer no existe, devuelve un mensaje de error
            if (!$chofer) {
                return response(["message" => "Chofer no encontrado"], Response::HTTP_NOT_FOUND);
            }

            // Retorna los datos del chofer
            return response()->json($chofer, 200);

        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTraslados($id)
    {
        try {
            // Busca al chofer con el ID proporcionado y carga la relación 'traslados'
            $chofer = Chofer::with('traslados')->find($id);

            if (!$chofer) {
                return Response::json(['error' => 'Chofer no encontrado'], 404);
            }

            // Obtén los traslados asociados al chofer
            $traslados = $chofer->traslados;

            // Puedes personalizar el formato de respuesta según tus necesidades
            $response = [
                'chofer' => $chofer,
                'traslados' => $traslados,
            ];

            return Response::json($response, 200);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
}
