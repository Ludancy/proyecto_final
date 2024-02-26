<?php
//api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChoferController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('users', [AuthController::class, 'allUsers']);
Route::post('register', [AuthController::class, 'register']);
Route::post('prueba-chofer', [ChoferController::class, 'storeCalificacion']);
