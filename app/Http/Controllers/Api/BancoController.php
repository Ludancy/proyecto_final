<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\PersonalAdmin;
use App\Models\Chofer;
use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class BancoController extends Controller
{
    // Obtener todos los bancos
    public function index()
    {
        $bancos = DB::table('bancos')->get();
        return response()->json($bancos);
    }

    // Obtener un banco por ID
    public function show($id)
    {
        $banco = DB::table('bancos')->find($id);

        if (!$banco) {
            return response()->json(['error' => 'Banco no encontrado'], 404);
        }

        return response()->json($banco);
    }

    // Crear un nuevo banco
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|string',
            // Agrega otros campos según tus necesidades
        ]);

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Insertar el nuevo banco en la tabla
            $bancoId = DB::table('bancos')->insertGetId([
                'nombre' => $request->input('nombre'),
                'codigo' => $request->input('codigo'),
                // Agrega otros campos según tus necesidades
            ]);

            // Confirmar la transacción
            DB::commit();

            // Obtener el banco recién creado
            $banco = DB::table('bancos')->find($bancoId);

            return response()->json($banco, 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|string',
            // Agrega otros campos según tus necesidades
        ]);

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Actualizar el banco en la tabla
            DB::table('bancos')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->input('nombre'),
                    'codigo' => $request->input('codigo'),
                    // Agrega otros campos según tus necesidades
                ]);

            // Confirmar la transacción
            DB::commit();

            // Obtener el banco actualizado
            $banco = DB::table('bancos')->find($id);

            return response()->json($banco, 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Eliminar un banco por ID
    public function destroy($id)
    {
        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Eliminar el banco de la tabla
            DB::table('bancos')->where('id', $id)->delete();

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Banco eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}