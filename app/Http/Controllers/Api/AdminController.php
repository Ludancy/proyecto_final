<?php

// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Traslado;
use App\Models\PersonalAdmin;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea

class AdminController extends Controller
{
    // En tu controlador de administrador (AdminController, por ejemplo)
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
            $traslados = Traslado::where('idChofer', $idChofer)
                ->where('estado', 'pendiente') // Ajusta según la lógica de tu aplicación
                ->get();

            foreach ($traslados as $traslado) {
                // Actualiza el estado y otros campos según tu lógica
                $traslado->update([
                    'estado' => 'cancelado',
                    'fecha_pago' => $request->input('fecha_pago'),
                    'referencia' => $request->input('referencia'),
                    'monto_pagado' => $request->input('monto_pagado'),
                ]);
            }

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
            $traslado = Traslado::where('id', $idTraslado)
                ->where('estado', 'pendiente') // Ajusta según la lógica de tu aplicación
                ->first();
    
            if (!$traslado) {
                return response()->json(['error' => 'Traslado no encontrado o no está pendiente'], 404);
            }
    
            // Actualiza el estado y otros campos según tu lógica
            $traslado->update([
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

    public function calcularGanancias(Request $request)
{
    // Validar la solicitud
    $request->validate([
        'fechaInicio' => 'required|date',
        'fechaFin' => 'required|date|after_or_equal:fechaInicio',
    ]);

    try {
        // Obtener los traslados en el período de tiempo especificado
        $traslados = Traslado::whereBetween('created_at', [$request->fechaInicio, $request->fechaFin])->get();

        // Calcular las ganancias sumando el 30% del costo de cada traslado
        $ganancias = $traslados->sum(function ($traslado) {
            return $traslado->costo * 0.3;
        });

        return response()->json(['ganancias' => $ganancias]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
public function verCancelacionesPorChofer(Request $request, $idChofer)
{
    // Validación de datos
    $request->validate([
        'fechaInicio' => 'required|date',
        'fechaFin' => 'required|date|after_or_equal:fechaInicio',
    ]);

    try {
        // Obtener traslados cancelados del chofer en el período de tiempo especificado
        $cancelaciones = Traslado::where('idChofer', $idChofer)
            ->where('estado', 'cancelado')
            ->whereBetween('created_at', [$request->fechaInicio, $request->fechaFin])
            ->get();

        // Ejemplo: Devolver las cancelaciones (puedes adaptarlo según tus necesidades)
        return response()->json(['cancelaciones' => $cancelaciones]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
