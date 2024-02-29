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
    public function cancelarTraslados(Request $request, $idChofer)
    {
        // Validación de datos
        $request->validate([
            'fecha_pago' => 'required|date',
            'referencia' => 'required|string',
            'monto_pagado' => 'required|numeric',
        ]);

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
    }
    

}
