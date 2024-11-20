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

Route::middleware(['api'])->group(function () {
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::post('me', [AuthController::class, 'me']);
  Route::post('/inspection', [InspectionController::class, 'store']);
  Route::post('/dialogue', [DailyDialogController::class, 'store']);
  Route::post('/pauseactive', [ActivePauseController::class, 'store']);
  Route::post('/alcoholtest', [AlcoholTestController::class, 'store']);
  Route::post('/controlgps', [GPSControlController::class, 'store']);
  Route::get('/sync', [SyncController::class, 'sync']);

});

Route::post('login', [AuthController::class, 'login']);
