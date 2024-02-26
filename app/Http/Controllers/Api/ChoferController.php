<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class ChoferController extends Controller
{

    // Agrega esta función al final de tu controlador ChoferController.php
    public function storeCalificacion(Request $request)
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'cedula' => 'required|unique:chofers',
            'fechaNacimiento' => 'required|date',
            // Agrega otras reglas de validación según tus necesidades
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $chofer = Chofer::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cedula' => $request->cedula,
            'fechaNacimiento' => $request->fechaNacimiento,
            // Agrega otros campos según tus necesidades
        ]);

        return response()->json($chofer, 201);
    }
}
