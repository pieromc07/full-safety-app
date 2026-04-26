<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
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


Route::middleware('jwt.verify')->group(function () {

  Route::get('/sync', [SyncController::class, 'sync']);

  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('me', [AuthController::class, 'me']);

  Route::post('/inspection', [SyncController::class, 'inspection']);
  Route::post('/inspections/massive', [SyncController::class, 'inspectionMassive']);
  Route::post('/dialogue', [SyncController::class, 'dailyDialog']);
  Route::post('/pauseactive', [SyncController::class, 'activePause']);
  Route::post('/alcoholtest', [SyncController::class, 'alcoholTest']);
  Route::post('/controlgps', [SyncController::class, 'controlGps']);

  Route::post('/ping', function () {
    return response()->json(['message' => 'pong']);
  });

  Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
