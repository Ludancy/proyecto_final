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

    public function updateInfo(Request $request, $id)
    {
        $vehiculo = Vehiculo::find($id);

        if (!$vehiculo) {
            return response()->json(['message' => 'No se encontró el vehículo.'], 404);
        }

        // Realizar validaciones y actualizaciones según tus necesidades
        $vehiculo->update([
            'marca' => $request->input('marca'),
            'color' => $request->input('color'),
            'placa' => $request->input('placa'),
            // ... Otras actualizaciones según tus necesidades
        ]);

        return response()->json(['message' => 'Información del vehículo actualizada con éxito']);
    }

    // Eliminar un vehículo
    public function delete($id)
    {
        $vehiculo = Vehiculo::find($id);

        if (!$vehiculo) {
            return response()->json(['message' => 'No se encontró el vehículo.'], 404);
        }

        // Realizar acciones previas a la eliminación si es necesario
        // ...

        $vehiculo->delete();

        return response()->json(['message' => 'Vehículo eliminado con éxito']);
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

    // Obtener la lista de vehículos aprobados
    public function obtenerVehiculosAprobados()
    {
        // Filtrar los vehículos por estado "Activo" y con alguna prueba aprobada (calificación >= 65)
        $vehiculosAprobados = Vehiculo::where('estado_vehiculo', 'Activo')
            ->whereHas('pruebasVehiculo', function ($query) {
                $query->where('calificacion', '>=', 65);
            })
            ->get();

        if ($vehiculosAprobados->isEmpty()) {
            return response()->json(['message' => 'No se encontraron vehículos aprobados.'], 404);
        }

        return response()->json($vehiculosAprobados);
    }

    public function obtenerVehiculosPendientesRevision()
    {
        // Filtrar los vehículos por estado "Pendiente de revisión"
        $vehiculosPendientesRevision = Vehiculo::where('estado_vehiculo', 'Pendiente')->get();

        if ($vehiculosPendientesRevision->isEmpty()) {
            return response()->json(['message' => 'No se encontraron vehículos pendientes de revisión.'], 404);
        }

        return response()->json($vehiculosPendientesRevision);
    }

    public function getInfo($id)
    {
        // Buscar el vehículo por ID junto con las pruebas de vehículo relacionadas
        $vehiculo = Vehiculo::with('pruebasVehiculo')->find($id);

        if (!$vehiculo) {
            return response()->json(['message' => 'No se encontró el vehículo.'], 404);
        }

        return response()->json($vehiculo);
    }

}
