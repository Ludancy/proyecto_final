<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use App\Models\PruebaVehiculo;
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

    public function getEvaluacionPsicologica($id)
    {
        // Validar que el chofer exista
        $validator = Validator::make(['idChofer' => $id], [
            'idChofer' => 'exists:chofers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 404);
        }

        // Obtener la evaluación psicológica del chofer
        $evaluacion = PruebaChofer::where('idChofer', $id)->orderBy('created_at', 'desc')->first();

        if (!$evaluacion) {
            return response()->json(['message' => 'No se encontró ninguna evaluación psicológica para el chofer.'], 404);
        }

        return response()->json($evaluacion);
    }
public function getChoferes()
{
    $choferes = Chofer::all(); // Obtén todos los choferes desde tu modelo

    return response()->json(['choferes' => $choferes], 200);
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

        // Actualizar un chofer por ID
        public function update(Request $request, $id)
        {
            $chofer = Chofer::find($id);
    
            if (!$chofer) {
                return response()->json(['message' => 'Chofer no encontrado'], 404);
            }
    
            // Validaciones y lógica para actualizar el chofer
            // ...
    
            $chofer->update($request->all());
    
            return response()->json(['message' => 'Chofer actualizado con éxito']);
        }
    
    // Eliminar un chofer por ID
    public function destroy($id)
    {
        $chofer = Chofer::find($id);

        if (!$chofer) {
            return response()->json(['message' => 'Chofer no encontrado'], 404);
        }
        $chofer->delete();

        $chofer->user()->delete();


        return response()->json(['message' => 'Chofer y registro en auths eliminados con éxito']);
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

    public function getVehiculos($id)
{
    try {
        // Busca al chofer con el ID proporcionado y carga la relación 'vehiculos'
        $chofer = Chofer::with('vehiculos')->find($id);

        if (!$chofer) {
            return Response::json(['error' => 'Chofer no encontrado'], 404);
        }

        // Obtén los vehículos registrados por el chofer
        $vehiculos = $chofer->vehiculos;

        // Puedes personalizar el formato de respuesta según tus necesidades
        $response = [
            'chofer' => $chofer        ];

        return Response::json($response, 200);
    } catch (\Exception $e) {
        return Response::json(['error' => $e->getMessage()], 500);
    }
}


public function obtenerResultadoEvaluacionVehiculo($idChofer, $idVehiculo)
{
    // Validar que el chofer exista
    $chofer = Chofer::with(['vehiculos' => function ($query) use ($idVehiculo) {
        $query->where('id', $idVehiculo);
    }])->find($idChofer);

    if (!$chofer) {
        return response()->json(['message' => 'No se encontró el chofer.'], 404);
    }

    // Obtener el vehículo asociado al chofer
    $vehiculo = $chofer->vehiculos->first();

    if (!$vehiculo || $vehiculo->id != $idVehiculo) {
        return response()->json(['message' => 'No se encontró un vehículo asociado al chofer.'], 404);
    }

    // Obtener la última evaluación de vehículo asociada al vehículo del chofer
    $evaluacionVehiculo = PruebaVehiculo::where('idVehiculo', $idVehiculo)
        ->first();

    if (!$evaluacionVehiculo) {
        return response()->json(['message' => 'No se encontró ninguna evaluación de vehículo para el chofer.'], 404);
    }

    return response()->json($evaluacionVehiculo);
}

// ... Otras funciones ...
}
