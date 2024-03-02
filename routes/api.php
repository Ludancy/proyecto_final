<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

use App\Http\Controllers\Api\ChoferController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TrasladoController;
use App\Http\Controllers\Api\PruebaController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\BancoController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/traslados', [ClienteController::class, 'obtenerTrasladosCliente']);

});


//AUTH
Route::get('users', [AuthController::class, 'allUsers']);

// Choferes

// CRUD para Choferes
Route::post('/agregar-banco-chofer/{idChofer}', [ChoferController::class, 'agregarBancoChofer']);
Route::post('/agregar-contactos-chofer/{idChofer}', [ChoferController::class, 'agregarContactosChofer']);
Route::get('choferes/{id}', [ChoferController::class, 'getInfo']);
Route::get('/choferes', [ChoferController::class, 'getChoferes']);
Route::put('/choferes/{id}', [ChoferController::class, 'update']); // Actualizar un chofer por ID
Route::delete('/choferes/{id}', [ChoferController::class, 'destroy']); // Eliminar un chofer por ID
Route::get('choferes/{id}/traslados', [ChoferController::class, 'getTraslados']);

    Route::post('prueba-chofer', [ChoferController::class, 'storeCalificacion']);
    // Referencia textual: "...ingresan la puntuación a las pruebas tanto de los choferes..."
    Route::post('/pruebas/evaluacion-psicologica', [ChoferController::class, 'evaluacionPsicologica']);
    Route::get('/choferes/{id}/evaluacion-psicologica', [ChoferController::class, 'getEvaluacionPsicologica']);
    Route::get('choferes/{id}/vehiculos', [ChoferController::class, 'getVehiculos']);
    Route::get('/choferes/{idChofer}/{idVehiculo}/evaluacion-vehiculo', [ChoferController::class, 'obtenerResultadoEvaluacionVehiculo']);
    Route::post('/traslados-realizados-chofer/{choferId}', [ChoferController::class, 'revisarTrasladosRealizados']);
     // Listado de Traslados Cancelados
     Route::get('chofer/cancelados/{choferId}', [ChoferController::class, 'trasladosCanceladosChofer']);

     // Listado de Traslados Pendientes por Cancelar
     Route::get('chofer/pendientes/{choferId}', [ChoferController::class, 'trasladosPendientesCancelarChofer']);

//CRUD para Vehiculos
Route::post('vehiculos/register', [VehiculoController::class, 'register']);
Route::get('/vehiculos/{id}', [VehiculoController::class, 'getInfo']);
Route::put('vehiculos/{id}/update', [VehiculoController::class, 'updateInfo']);
Route::delete('vehiculos/{id}/delete', [VehiculoController::class, 'delete']);

    Route::post('pruebas/evaluacion-vehiculo', [VehiculoController::class, 'evaluacionVehiculo']);
    // Obtener la lista de vehículos aprobados
    Route::get('/vehiculos/aprobados', [VehiculoController::class, 'obtenerVehiculosAprobados']);
    // Obtener la lista de vehículos pendientes de revisión
    Route::get('/vehiculos/pendientes-revision', [VehiculoController::class, 'obtenerVehiculosPendientesRevision']);


// Clientes
Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'index']); // Obtener todos los clientes
    Route::get('/{id}', [ClienteController::class, 'show']); // Obtener un cliente por ID
    Route::put('/{id}', [ClienteController::class, 'update']); // Actualizar un cliente por ID
    Route::delete('/{id}', [ClienteController::class, 'destroy']); // Eliminar un cliente por ID
});
Route::post('/{idCliente}/traslados/solicitar', [ClienteController::class, 'solicitarTraslado']);
Route::get('cliente/traslados/{clienteId}', [ClienteController::class, 'trasladosCliente']);
Route::get('/historial-recargas', [ClienteController::class, 'historialRecargasCliente']);
Route::get('/historial-recargas/{clienteId}', [ClienteController::class, 'historialRecargasCliente']);


// Administrativo
Route::post('/calcular-ganancias', [AdminController::class, 'calcularGanancias']);

Route::post('choferes/{id}/traslados/cancelar', [AdminController::class, 'cancelarTraslados']);
Route::post('/ver-cancelaciones-chofer/{idChofer}', [AdminController::class, 'verCancelacionesPorChofer']);
Route::post('/cancelar-traslado/{idTraslado}', [AdminController::class, 'cancelarTraslado']);


// Rutas para el CRUD de Bancos
// Referencia textual: "...datos de cualquier otra tabla base que el sistema requiera, como por ejemplo bancos."

Route::prefix('bancos')->group(function () {
    Route::get('/', [BancoController::class, 'index']); // Obtener todos los bancos
    Route::get('/{id}', [BancoController::class, 'show']); // Obtener un banco por ID
    Route::post('/register', [BancoController::class, 'store']); // Crear un nuevo banco
    Route::put('/{id}/update', [BancoController::class, 'update']); // Actualizar un banco por ID
    Route::delete('/{id}/delete', [BancoController::class, 'destroy']); // Eliminar un banco por ID
});
// Traslados


// Pruebas

Route::post('/clientes/{idCliente}/recarga-saldo', [ClienteController::class, 'recargaSaldo']);


// falta relacion de saldo y vehiculos