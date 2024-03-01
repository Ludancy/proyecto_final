<?php

namespace App\Http\Controllers\Api;

use App\Models\Chofer;
use App\Models\PruebaChofer;
use App\Models\Traslado;
use App\Models\PruebaVehiculo;
use App\Models\ContactoEmergenciaChofer;
use App\Models\BancoChofer;
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
        // Encuentra al chofer por su ID con las relaciones "banco" y "contactosEmergencia" cargadas
        $chofer = Chofer::with(['cuentasBancarias', 'contactosEmergencia'])->find($id);

        // Si el chofer no existe, devuelve un mensaje de error
        if (!$chofer) {
            return response(["message" => "Chofer no encontrado"], Response::HTTP_NOT_FOUND);
        }

        // Retorna los datos del chofer con los datos del banco y los contactos de emergencia
        return response()->json($chofer, 200);

    } catch (\Exception $e) {
        // Manejo de excepciones
        return response(["error" => $e->getMessage()]);
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

    public function revisarTrasladosRealizados(Request $request, $choferId)
        {
            try {
                // Obtener el chofer por ID
                $chofer = Chofer::find($choferId);

                // Validar si el chofer existe
                if (!$chofer) {
                    return response()->json(['message' => 'Chofer no encontrado.'], 404);
                }


                // Validar datos de la solicitud
                $request->validate([
                    'fecha_inicio' => 'required|date',
                    'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                ]);

                // Obtener los traslados realizados por el chofer en un período de tiempo
                $traslados = Traslado::where('idChofer', $chofer->id)
                    ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Puedes personalizar la respuesta según tus necesidades
                return response()->json(['traslados_realizados' => $traslados]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function trasladosCanceladosChofer($choferId)
        {
            try {
                $trasladosCancelados = Traslado::where('idChofer', $choferId)
                    ->where('estado', 'cancelado')
                    ->get();
    
                return response()->json(['trasladosCancelados' => $trasladosCancelados]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    
        // Listado de Traslados Pendientes por Cancelar
        public function trasladosPendientesCancelarChofer($choferId)
        {
            try {
                $trasladosPendientes = Traslado::where('idChofer', $choferId)
                    ->where('estado', 'pendiente') // Ajusta según la lógica de tu aplicación
                    ->get();
    
                return response()->json(['trasladosPendientes' => $trasladosPendientes]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function agregarContactosChofer(Request $request, $idChofer)
        {
            try {
                // Validar que el chofer exista
                $chofer = Chofer::findOrFail($idChofer);
        
                // Validar datos de la solicitud
                $request->validate([
                    'contactosEmergencia' => 'required|array|min:2', // al menos dos contactos
                    'contactosEmergencia.*.nombre' => 'required|string|max:255',
                    'contactosEmergencia.*.telefono' => 'required|string|max:20',
                ]);
        
                // Agregar cada contacto de emergencia al chofer
                foreach ($request->contactosEmergencia as $contacto) {
                    ContactoEmergenciaChofer::create([
                        'idChofer' => $idChofer,
                        'nombre' => $contacto['nombre'],
                        'telefono' => $contacto['telefono'],
                        // Puedes agregar más campos según tus necesidades
                    ]);
                }
        
                return response()->json(['message' => 'Contactos de emergencia agregados con éxito.']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function agregarBancoChofer(Request $request, $idChofer)
{
    try {
        // Validar que el chofer exista
        $chofer = Chofer::findOrFail($idChofer);

        // Validar datos de la solicitud
        $request->validate([
            'idBanco' => 'required|exists:bancos,id',
            'nroCuenta' => 'required|string|max:255',
            'estado' => 'nullable|string|max:255',
        ]);

        // Agregar la relación entre el chofer y el banco
        BancoChofer::create([
            'idChofer' => $idChofer,
            'idBanco' => $request->idBanco,
            'nroCuenta' => $request->nroCuenta,
            'estado' => $request->estado,
        ]);

        return response()->json(['message' => 'Datos bancarios del chofer agregados con éxito.']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
