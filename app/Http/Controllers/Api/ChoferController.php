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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;



use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class ChoferController extends Controller
{

    // Agrega esta función al final de tu controlador ChoferController.php
    public function evaluacionPsicologica(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'idChofer' => 'required|exists:chofers,id',
            'calificacion' => 'required|numeric|min:0|max:100',
            // Agrega otras reglas de validación según tus necesidades
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Crear la evaluación psicológica directamente en la base de datos
            $pruebaChoferId = DB::table('pruebachofer')->insertGetId([
                'idChofer' => $request->input('idChofer'),
                'calificacion' => $request->input('calificacion'),
                // Agrega otros campos según tus necesidades
            ]);

            // Confirmar la transacción
            DB::commit();

            // Obtener la evaluación psicológica recién creada
            $pruebaChofer = DB::table('pruebachofer')->find($pruebaChoferId);

            return response()->json($pruebaChofer, 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Agrega esta función al final de tu controlador ChoferController.php
    public function getEvaluacionPsicologica($id)
    {
        // Validar que el chofer exista
        $validator = Validator::make(['idChofer' => $id], [
            'idChofer' => 'exists:chofers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 404);
        }

        try {
            // Obtener la evaluación psicológica del chofer
            $evaluacion = DB::table('pruebachofer')
                ->where('idChofer', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$evaluacion) {
                return response()->json(['message' => 'No se encontró ninguna evaluación psicológica para el chofer.'], 404);
            }

            return response()->json($evaluacion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getChoferes()
    {
        try {
            // Obtener todos los choferes utilizando una consulta sin Eloquent ORM
            $choferes = DB::table('chofers')->get();

            return response()->json(['choferes' => $choferes], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], 500);
        }
    }
    // Obtener información de un chofer por ID con relaciones sin ORM
    public function getInfo($id)
    {
        try {
            // Obtener datos del chofer sin Eloquent ORM
            $chofer = DB::table('chofers')
                ->where('chofers.id', $id)
                ->first();

            // Si el chofer no existe, devuelve un mensaje de error
            if (!$chofer) {
                return response(["message" => "Chofer no encontrado"], 404);
            }

            // Obtener las cuentas bancarias utilizando una consulta sin Eloquent ORM
            $cuentasBancarias = DB::table('banco_chofer')
                ->join('bancos', 'banco_chofer.idBanco', '=', 'bancos.id')
                ->where('banco_chofer.idChofer', $id)
                ->select('banco_chofer.*', 'bancos.nombre as entidadBancaria', 'bancos.codigo as numeroCuenta')
                ->get();

            // Obtener contactos de emergencia utilizando una consulta sin Eloquent ORM
            $contactosEmergencia = DB::table('contacto_emergencia_chofer')
                ->where('idChofer', $id)
                ->get();

            // Agregar las cuentas bancarias y contactos de emergencia a los datos del chofer
            $chofer->cuentas_bancarias = $cuentasBancarias;
            $chofer->contactos_emergencia = $contactosEmergencia;

            return response()->json($chofer, 200);

        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], 500);
        }
    }




    // Actualizar un chofer por ID sin usar Eloquent ORM
    public function update(Request $request, $id)
    {
        try {
            // Verificar si el chofer existe
            $chofer = DB::table('chofers')->where('id', $id)->first();

            if (!$chofer) {
                return response()->json(['message' => 'Chofer no encontrado'], 404);
            }

            // Validaciones y lógica para actualizar el chofer
            // ...

            // Actualizar el chofer sin Eloquent ORM
            DB::table('chofers')->where('id', $id)->update($request->all());

            return response()->json(['message' => 'Chofer actualizado con éxito']);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], 500);
        }
    }
    
    // Eliminar un chofer por ID sin usar Eloquent ORM
    public function destroy($id)
    {
        try {
            // Verificar si el chofer existe
            $chofer = DB::table('chofers')->where('id', $id)->first();

            if (!$chofer) {
                return response()->json(['message' => 'Chofer no encontrado'], 404);
            }

            // Eliminar el chofer y su registro en auths sin Eloquent ORM
            DB::table('chofers')->where('id', $id)->delete();
            DB::table('auths')->where('id', $chofer->idAuth)->delete();

            return response()->json(['message' => 'Chofer y registro en auths eliminados con éxito']);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], 500);
        }
    }


    public function getTraslados($id)
    {
        try {
            // Realizar una consulta SQL para obtener los traslados del chofer con el ID proporcionado
            $traslados = DB::table('traslados')
                ->where('idChofer', $id)
                ->get();

            // Verificar si el chofer existe
            $chofer = DB::table('chofers')->where('id', $id)->first();

            if (!$chofer) {
                return response()->json(['error' => 'Chofer no encontrado'], 404);
            }

            // Puedes personalizar el formato de respuesta según tus necesidades
            $response = [
                'chofer' => $chofer,
                'traslados' => $traslados,
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

 
// Obtener los vehículos de un chofer sin usar Eloquent ORM
    public function getVehiculos($id)
    {
        try {
            // Realizar una consulta SQL para obtener los vehículos del chofer con el ID proporcionado
            $vehiculos = DB::table('vehiculos')
                ->where('idChofer', $id)
                ->select('id', 'idChofer', 'marca', 'color', 'placa', 'anio_fabricacion', 'estado_vehiculo', 'estado_actual', 'created_at', 'updated_at')
                ->get();

            // Verificar si el chofer existe
            $chofer = DB::table('chofers')->where('id', $id)->first();

            if (!$chofer) {
                return response()->json(['error' => 'Chofer no encontrado'], 404);
            }

            // Puedes personalizar el formato de respuesta según tus necesidades
            $response = [
                'chofer' => [
                    'id' => $chofer->id,
                    'nombre' => $chofer->nombre,
                    'apellido' => $chofer->apellido,
                    'cedula' => $chofer->cedula,
                    'fechaNacimiento' => $chofer->fechaNacimiento,
                    'idAuth' => $chofer->idAuth,
                    'entidadBancaria' => $chofer->entidadBancaria,
                    'numeroCuenta' => $chofer->numeroCuenta,
                    'saldo' => $chofer->saldo,
                    'created_at' => $chofer->created_at,
                    'updated_at' => $chofer->updated_at,
                    'vehiculos' => $vehiculos,
                ],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getCuentasBancarias($idChofer)
    {
        try {
            // Obtener cuentas bancarias utilizando una consulta sin Eloquent ORM
            $cuentasBancarias = DB::table('banco_chofer')
                ->join('bancos', 'banco_chofer.idBanco', '=', 'bancos.id')
                ->where('banco_chofer.idChofer', $idChofer)
                ->select('bancos.nombre', 'bancos.codigo as nroCuenta')
                ->get();

            return response()->json($cuentasBancarias, 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response(["error" => $e->getMessage()], 500);
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
        $traslados = Traslado::select(
            'traslados.id',
            'idChofer',
            'idCliente',
            'costo',
            'estado',
            'idVehiculo',
            'traslados.created_at',
            'traslados.updated_at',
            'origen',
            'destino',
            \DB::raw('lugares.nombre as origennombre'),
            \DB::raw('destinos.nombre as destinonombre')
        )
        ->leftJoin('lugares', 'traslados.origen', '=', 'lugares.id')
        ->leftJoin('lugares as destinos', 'traslados.destino', '=', 'destinos.id')
        ->where('idChofer', $chofer->id)
        ->whereBetween('traslados.created_at', [$request->fecha_inicio, $request->fecha_fin])
        ->orderBy('traslados.created_at', 'desc')
        ->get();

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
