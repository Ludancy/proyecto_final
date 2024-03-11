<?php

// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Traslado;
use App\Models\PersonalAdmin;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class AdminController extends Controller
{
    public function cancelarTraslados(Request $request, $idChofer)
    {
        // Validación de datos
        $request->validate([
            'fecha_pago' => 'required|date',
            'referencia' => 'required|string',
            'monto_pagado' => 'required|numeric',
        ]);
    
        try {
            // Lógica para cancelar traslados del chofer con ID $idChofer
            DB::table('traslados')
                ->where('idChofer', $idChofer)
                ->where('estado', 'pendiente')
                ->update([
                    'estado' => 'cancelado',
                    'fecha_pago' => $request->input('fecha_pago'),
                    'referencia' => $request->input('referencia'),
                    'monto_pagado' => $request->input('monto_pagado'),
                ]);
    
            // Ejemplo: Devolver una respuesta (puedes adaptarlo según tus necesidades)
            return response()->json(['mensaje' => 'Traslados cancelados exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function cancelarTraslado(Request $request, $idTraslado)
    {
        // Validación de datos
        $request->validate([
            'fecha_pago' => 'required|date',
            'referencia' => 'required|string',
            'monto_pagado' => 'required|numeric',
        ]);
    
        try {
            // Lógica para cancelar un solo traslado con ID $idTraslado
            $traslado = DB::table('traslados')
                ->where('id', $idTraslado)
                ->where('estado', 'pendiente')
                ->first();
    
            if (!$traslado) {
                return response()->json(['error' => 'Traslado no encontrado o no está pendiente'], 404);
            }
    
            // Actualiza el estado y otros campos según tu lógica
            DB::table('traslados')
                ->where('id', $idTraslado)
                ->update([
                    'estado' => 'cancelado',
                    'fecha_pago' => $request->input('fecha_pago'),
                    'referencia' => $request->input('referencia'),
                    'monto_pagado' => $request->input('monto_pagado'),
                ]);
    
            // Ejemplo: Devolver una respuesta (puedes adaptarlo según tus necesidades)
            return response()->json(['mensaje' => 'Traslado cancelado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function trasladosPorFecha(Request $request)
    {
        try {
            // Validar datos de la solicitud
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);
    
            // Obtener los traslados realizados en un período de tiempo
            $traslados = DB::table('traslados')
                ->select(
                    'traslados.id',
                    'idChofer',
                    'chofers.nombre as nombre_chofer', // Agregado
                    'idCliente',
                    'cliente.nombre as nombre_cliente', // Agregado
                    'costo',
                    'estado',
                    'idVehiculo',
                    'traslados.fecha_creacion',
                    'origen',
                    'destino',
                    'lugares.nombre as nombre_origen',
                    'destinos.nombre as nombre_destino'
                )
                ->leftJoin('lugares', 'traslados.origen', '=', 'lugares.id')
                ->leftJoin('lugares as destinos', 'traslados.destino', '=', 'destinos.id')
                ->leftJoin('chofers', 'traslados.idChofer', '=', 'chofers.id') // Agregado
                ->leftJoin('cliente', 'traslados.idCliente', '=', 'cliente.id') // Agregado
                ->whereBetween('traslados.fecha_creacion', [$request->fecha_inicio, $request->fecha_fin])
                ->orderBy('traslados.fecha_creacion', 'desc')
                ->get();
    
            // Calcular las ganancias llamando al método calcularGanancias
            $ganancias = $this->calcularGanancias($request);
    
            // Agregar las ganancias al array de respuesta
            $responseArray = [
                'ganancias' => $ganancias,
                'traslados_realizados' => $traslados,
            ];
    
            return response()->json($responseArray);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function calcularGanancias(Request $request)
    {
        try {
            // Validar datos de la solicitud
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);
    
            // Obtener las ganancias sumando el 30% del costo de cada traslado
            $ganancias = DB::table('traslados')
                ->whereBetween('fecha_creacion', [$request->fecha_inicio, $request->fecha_fin])
                ->sum(DB::raw('costo * 0.3'));
    
            // Convertir las ganancias a un entero o un valor de punto flotante
            return floatval($ganancias); // Para obtener un valor de punto flotante
            // return intval($ganancias); // Para obtener un valor entero
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function verCancelacionesPorChofer(Request $request, $idChofer)
    {
        // Validación de datos
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
    
        try {
            // Obtener traslados cancelados del chofer en el período de tiempo especificado
            $cancelaciones = DB::table('traslados')
                ->where('idChofer', $idChofer)
                ->where('estado', 'cancelado')
                ->whereBetween('fecha_pago', [$request->fecha_inicio, $request->fecha_fin])
                ->get();
    
            // Ejemplo: Devolver las cancelaciones (puedes adaptarlo según tus necesidades)
            return response()->json(['cancelaciones' => $cancelaciones]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
