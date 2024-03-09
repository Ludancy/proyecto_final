<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use App\Models\Vehiculo;
use App\Models\PruebaVehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Crear un nuevo vehículo
            $vehiculoId = DB::table('vehiculos')->insertGetId([
                'idChofer' => $request->idChofer,
                'marca' => $request->marca,
                'color' => $request->color,
                'placa' => $request->placa,
                'anio_fabricacion' => $request->anio_fabricacion,
                'estado_vehiculo' => $request->estado_vehiculo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $vehiculo = DB::table('vehiculos')->find($vehiculoId);
    
            return response()->json(['message' => 'Vehículo registrado correctamente', 'vehiculo' => $vehiculo], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateInfo(Request $request, $id)
    {
        try {
            // Buscar el vehículo por ID
            $vehiculo = DB::table('vehiculos')->find($id);
    
            if (!$vehiculo) {
                return response()->json(['message' => 'No se encontró el vehículo.'], 404);
            }
    
            // Realizar validaciones y actualizaciones según tus necesidades
            DB::table('vehiculos')->where('id', $id)->update([
                'idChofer' => $request->idChofer,
                'marca' => $request->marca,
                'color' => $request->color,
                'placa' => $request->placa,
                'anio_fabricacion' => $request->anio_fabricacion,
                'estado_vehiculo' => $request->estado_vehiculo,
                // ... Otras actualizaciones según tus necesidades
                'updated_at' => now(),
            ]);
    
            return response()->json(['message' => 'Información del vehículo actualizada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            // Verificar si el vehículo existe
            $vehiculo = DB::table('vehiculos')->find($id);
    
            if (!$vehiculo) {
                return response()->json(['message' => 'No se encontró el vehículo.'], 404);
            }
    
            // Realizar acciones previas a la eliminación si es necesario
            // ...
    
            // Eliminar el vehículo
            DB::table('vehiculos')->where('id', $id)->delete();
    
            return response()->json(['message' => 'Vehículo eliminado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



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
        // Registrar la prueba del vehículo
        $pruebaVehiculoId = DB::table('pruebavehiculo')->insertGetId([
            'idVehiculo' => $request->idVehiculo,
            'calificacion' => $request->calificacion,
            // Agrega otros campos según tus necesidades
            'fecha_creacion' => now(),
        ]);

        // Obtener la información del vehículo
        $vehiculo = DB::table('vehiculos')->find($request->idVehiculo);

        // Actualizar el estado del vehículo a 'Aprobado' si la calificación es mayor que 65
        if ($request->calificacion > 65) {
            DB::table('vehiculos')->where('id', $request->idVehiculo)->update(['estado_vehiculo' => 'Aprobado']);
        }

        return response()->json(['message' => 'Evaluación de vehículo registrada con éxito'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function obtenerVehiculosAprobados()
    {
        // Filtrar los vehículos por estado "Activo" y con alguna prueba aprobada (calificación >= 65)
        $vehiculosAprobados = DB::table('vehiculos')
            ->where('estado_vehiculo', 'Aprobado')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pruebavehiculo')
                    ->whereRaw('pruebavehiculo.idVehiculo = vehiculos.id')
                    ->where('calificacion', '>=', 65);
            })
            ->get();

        if (empty($vehiculosAprobados)) {
            return response()->json(['message' => 'No se encontraron vehículos aprobados.'], 404);
        }

        return response()->json($vehiculosAprobados);
    }

    public function obtenerVehiculosPendientesRevision()
    {
        // Filtrar los vehículos por estado "Pendiente de revisión"
        $vehiculosPendientesRevision = DB::table('vehiculos')
            ->where('estado_vehiculo', 'Pendiente')
            ->get();
    
        if (empty($vehiculosPendientesRevision)) {
            return response()->json(['message' => 'No se encontraron vehículos pendientes de revisión.'], 404);
        }
    
        return response()->json($vehiculosPendientesRevision);
    }

    public function getInfo($id)
    {
        // Buscar el vehículo por ID junto con las pruebas de vehículo relacionadas
        $vehiculo = DB::table('vehiculos')
            ->leftJoin('pruebavehiculo', 'vehiculos.id', '=', 'pruebavehiculo.idVehiculo')
            ->select('vehiculos.*', 'pruebavehiculo.calificacion')
            ->where('vehiculos.id', $id)
            ->first();
    
        if (!$vehiculo) {
            return response()->json(['message' => 'No se encontró el vehículo.'], 404);
        }
    
        return response()->json($vehiculo);
    }

}
