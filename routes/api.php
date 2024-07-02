<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\TruckLoggerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [AuthenticationController::class, 'store']);
    Route::post('logout', [AuthenticationController::class, 'destroy'])->middleware('auth:api');
  });

Route::group(['prefix' => 'v1', 'middleware' => 'with_fast_api_key'], function () {
    Route::resource('/vehicle', App\Http\Controllers\TruckLoggerController::class);
    Route::post('/vehicles/bulk', [App\Http\Controllers\TruckLoggerController::class, 'insertMultiple']);
});