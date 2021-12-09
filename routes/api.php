<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    //Aquí van todas las rutas que queramos proteger
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/genders', [GenderController::class, 'index']); //Listar Generos
    Route::post('/genders/add', [GenderController::class, 'store']); //Registrar
    Route::put('/genders/update/{id}', [GenderController::class, 'update']); //Actualizar
     Route::delete('/genders/delete/{id}', [GenderController::class, 'destroy']);//Eliminar

     Route::get('/pets/{limit?}', [PetController::class,'index']);//Listar paginado





});

Route::post('/login', [UserController::class, 'login']);
