<?php

use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\UsersController;
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

/* Unprotected routes */

Route::post('login', [UsersController::class, 'login']);
Route::post('logout/{id}', [UsersController::class, 'logout']);

Route::apiResource('user', UsersController::class)
        ->only(['store']);

/* Sanctum protected routes */
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('user', UsersController::class)
        ->only(['index', 'show', 'destroy']);
    
    Route::apiResource('car', CarsController::class);

    Route::apiResource('category', CarCategoryController::class);
});