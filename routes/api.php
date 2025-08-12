<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\TablesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function(){

    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('/tables',TablesController::class);
    Route::apiResource('/orders', OrdersController::class);
    Route::apiResource('/menu-items', MenuItemController::class);
    Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/users', UserController::class);



});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
