<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use App\Models\Vehiculo;
use App\Models\PruebaVehiculo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;



use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class VehiculoController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validación de los datos del vehículo
            $validator = Validator::make($request->all(), [
                'idChofer' => 'required|exists:chofers,id',
                'marca' => 'required|string',
                'color' => 'required|string',
                'placa' => 'required|string',
                'anio_fabricacion' => 'required|integer',
                'estado_vehiculo' => 'required|string',
            ]);

            if ($validator->fails()) {
                return Response::json(['error' => $validator->errors()], 400);
            }

            // Crear un nuevo vehículo
            $vehiculo = Vehiculo::create([
                'idChofer' => $request->idChofer,
                'marca' => $request->marca,
                'color' => $request->color,
                'placa' => $request->placa,
                'anio_fabricacion' => $request->anio_fabricacion,
                'estado_vehiculo' => $request->estado_vehiculo,
            ]);

            return Response::json(['message' => 'Vehículo registrado correctamente', 'vehiculo' => $vehiculo], 201);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

// Agrega esta función al final de tu controlador ChoferController.php
public function evaluacionVehiculo(Request $request)
{
    $validator = Validator::make($request->all(), [
        'idVehiculo' => 'required|exists:vehiculos,id',
        'calificacion' => 'required|numeric|min:0|max:100',
        // Agrega otras reglas de validación según tus necesidades
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        $pruebaVehiculo = PruebaVehiculo::create([
            'idVehiculo' => $request->idVehiculo,
            'calificacion' => $request->calificacion,
            // Agrega otros campos según tus necesidades
        ]);

        return response()->json(['message' => 'Evaluación de vehículo registrada con éxito'], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
