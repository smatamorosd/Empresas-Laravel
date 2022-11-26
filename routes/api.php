<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EmpresaController;
use \App\Http\Controllers\EmpleadoController;
use \App\Http\Controllers\EmpresaEmpleadoController;
use App\Http\Controllers\JuegoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
#     return $request->user();
# });

Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('empleados', EmpleadoController::class)->only(['index', 'show']);
Route::apiresource('empresas.empleados',EmpresaEmpleadoController::class)->except(['show']);
Route::apiResource("juegos", JuegoController::class);
//Route::get("juegos.actualizar", JuegoController::class);
//Route::apiResource("juegos.nombre", JuegoController::class);