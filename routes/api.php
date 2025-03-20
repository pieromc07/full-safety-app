<?php

use App\Http\Controllers\ActivePauseController;
use App\Http\Controllers\AlcoholTestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\DailyDialogController;
use App\Http\Controllers\GPSControlController;
use App\Http\Controllers\InspectionController;
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


Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('me', [AuthController::class, 'me']);
Route::post('/inspection', [SyncController::class, 'inspection'])->middleware('auth:api');
Route::post('/dialogue', [SyncController::class, 'dailyDialog'])->middleware('auth:api');
Route::post('/pauseactive', [SyncController::class, 'activePause'])->middleware('auth:api');
Route::post('/alcoholtest', [SyncController::class, 'alcoholTest'])->middleware('auth:api');
Route::post('/controlgps', [SyncController::class, 'controlGps'])->middleware('auth:api');
Route::post('/ping', function () {
  return response()->json(['message' => 'pong']);
})->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/sync', [SyncController::class, 'sync']);
Route::post('/login', [AuthController::class, 'login']);
