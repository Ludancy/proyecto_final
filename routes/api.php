<?php
//api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChoferController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TrasladoController;
use App\Http\Controllers\Api\PruebaController;
use App\Http\Controllers\Api\VehiculoController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});
//AUTH
Route::get('users', [AuthController::class, 'allUsers']);
Route::post('register', [AuthController::class, 'register']);

// Choferes
Route::post('prueba-chofer', [ChoferController::class, 'storeCalificacion']);
Route::get('choferes/{id}', [ChoferController::class, 'getInfo']);
Route::post('/pruebas/evaluacion-psicologica', [ChoferController::class, 'evaluacionPsicologica']);
Route::get('choferes/{id}/vehiculos', [ChoferController::class, 'getVehiculos']);
Route::get('choferes/{id}/traslados', [ChoferController::class, 'getTraslados']);

//Vehiculos
Route::post('vehiculos/register', [VehiculoController::class, 'register']);
Route::get('vehiculos/{id}', [VehiculoController::class, 'getInfo']);
Route::put('vehiculos/{id}/update', [VehiculoController::class, 'updateInfo']);
Route::delete('vehiculos/{id}/delete', [VehiculoController::class, 'delete']);
Route::post('pruebas/evaluacion-vehiculo', [VehiculoController::class, 'evaluacionVehiculo']);

//WIP--------------------
// Clientes
Route::post('clientes/register', [ClienteController::class, 'register']);
Route::post('clientes/login', [ClienteController::class, 'login']);
Route::get('clientes/{id}', [ClienteController::class, 'getInfo']);
Route::post('clientes/recargar-saldo', [ClienteController::class, 'recargarSaldo']);
Route::post('clientes/solicitar-traslado', [ClienteController::class, 'solicitarTraslado']);
Route::get('clientes/{id}/historial-recargas', [ClienteController::class, 'historialRecargas']);
Route::get('clientes/{id}/traslados', [ClienteController::class, 'getTraslados']);

// Administrativo
Route::post('admin/register', [AdminController::class, 'register']);
Route::post('admin/login', [AdminController::class, 'login']);
Route::get('admin/traslados-cancelados', [AdminController::class, 'trasladosCancelados']);
Route::get('admin/traslados-pendientes', [AdminController::class, 'trasladosPendientes']);
Route::post('admin/registrar-puntuacion', [AdminController::class, 'registrarPuntuacion']);
Route::get('admin/{id}', [AdminController::class, 'getInfo']);
Route::get('admin/recaudacion/{periodo}', [AdminController::class, 'recaudacion']);
Route::get('admin/pago-chofer/{chofer_id}/{periodo}', [AdminController::class, 'pagoChofer']);

// Traslados
Route::post('traslados/asignar-chofer', [TrasladoController::class, 'asignarChofer']);
Route::get('traslados/{id}', [TrasladoController::class, 'getInfo']);
Route::get('traslados/realizados/{periodo}', [TrasladoController::class, 'trasladosRealizados']);

// Pruebas
