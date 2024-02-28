<?php
//api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChoferController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TrasladoController;
use App\Http\Controllers\Api\PruebaController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\ClienteController;


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

// CRUD para Choferes
Route::get('choferes/{id}', [ChoferController::class, 'getInfo']);
Route::get('/choferes', [ChoferController::class, 'getChoferes']);
Route::put('/choferes/{id}', [ChoferController::class, 'update']); // Actualizar un chofer por ID
Route::delete('/choferes/{id}', [ChoferController::class, 'destroy']); // Eliminar un chofer por ID

    Route::post('prueba-chofer', [ChoferController::class, 'storeCalificacion']);
    Route::post('/pruebas/evaluacion-psicologica', [ChoferController::class, 'evaluacionPsicologica']);
    Route::get('/choferes/{id}/evaluacion-psicologica', [ChoferController::class, 'getEvaluacionPsicologica']);
    Route::get('choferes/{id}/vehiculos', [ChoferController::class, 'getVehiculos']);
    Route::get('/choferes/{idChofer}/{idVehiculo}/evaluacion-vehiculo', [ChoferController::class, 'obtenerResultadoEvaluacionVehiculo']);

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


// Administrativo


// Traslados


// Pruebas


// FALTA ESTE ENDPOINT -----ENDPOINTS EN COLA----------
Route::get('choferes/{id}/traslados', [ChoferController::class, 'getTraslados']);