<?php
//api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChoferController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TrasladoController;
use App\Http\Controllers\Api\PruebaController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\BancoController;


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

Route::post('choferes/{id}/traslados/cancelar', [AdminController::class, 'cancelarTraslados']);


// Rutas para el CRUD de Bancos
Route::prefix('bancos')->group(function () {
    Route::get('/', [BancoController::class, 'index']); // Obtener todos los bancos
    Route::get('/{id}', [BancoController::class, 'show']); // Obtener un banco por ID
    Route::post('/register', [BancoController::class, 'store']); // Crear un nuevo banco
    Route::put('/{id}/update', [BancoController::class, 'update']); // Actualizar un banco por ID
    Route::delete('/{id}/delete', [BancoController::class, 'destroy']); // Eliminar un banco por ID
});
// Traslados


// Pruebas




// FALTA ESTE ENDPOINT -----ENDPOINTS EN COLA----------
Route::get('choferes/{id}/traslados', [ChoferController::class, 'getTraslados']);

// FALTA TRABAJAR NADA EHCHO
Route::post('/clientes/{idCliente}/recarga-saldo', [ClienteController::class, 'recargaSaldo']);


















// Cancelar traslados a choferes:
//--- de este falta mejorar
// Endpoint: /cancelar_traslados
// Referencia textual: "...cancelarle a los choferes los traslados, para ello se debe indicar la fecha del pago, la referencia y el monto pagado."
// Ingresar puntuación a choferes:

// Endpoint: /ingresar_puntuacion_chofer
// Referencia textual: "...ingresan la puntuación a las pruebas tanto de los choferes..."
// Ingresar puntuación a vehículos:

// Endpoint: /ingresar_puntuacion_vehiculo
// Referencia textual: "...pruebas tanto de los vehículos..."
// Ingresar datos a tablas base (por ejemplo, bancos):

// Endpoint: /ingresar_datos_tabla_base
// Referencia textual: "...datos de cualquier otra tabla base que el sistema requiera, como por ejemplo bancos."
// Ver recaudación por ganancias en un periodo de tiempo:

// Endpoint: /recaudacion_ganancias
// Referencia textual: "...ver lo recaudado por la empresa por concepto de ganancias dado un periodo de tiempo..."
// Ver pagos a un chofer en un periodo de tiempo:

// Endpoint: /pagos_chofer
// Referencia textual: "...y lo cancelado a un chofer en específico dado un periodo de tiempo."