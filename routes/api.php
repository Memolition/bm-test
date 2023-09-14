<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\UsersController;
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

/* Unprotected routes */

Route::post('login', [UsersController::class, 'login']);
Route::post('logout/{id}', [UsersController::class, 'logout']);


/* Sanctum protected routes */
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::apiResource('user', UsersController::class)
    ->only(['index', 'show', 'store', 'destroy'])
    ->middleware('auth:sanctum');

Route::resource('car', CarsController::class);

