<?php

use App\Http\Controllers\ActivePauseController;
use App\Http\Controllers\AlcoholTestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\DailyDialogController;
use App\Http\Controllers\GPSControlController;
use App\Http\Controllers\InspectionController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Report;

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

  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('me', [AuthController::class, 'me']);

  Route::post('/inspection', [SyncController::class, 'inspection']);
  Route::post('/dialogue', [SyncController::class, 'dailyDialog']);
  Route::post('/pauseactive', [SyncController::class, 'activePause']);
  Route::post('/alcoholtest', [SyncController::class, 'alcoholTest']);
  Route::post('/controlgps', [SyncController::class, 'controlGps']);

  Route::post('/ping', function () {
    return response()->json(['message' => 'pong']);
  });

  Route::post('/refresh', [AuthController::class, 'refresh']);
});
// Route::get('/test-email', function () {

//   try {
//     Mail::raw("Correo de prueba", function ($message) {
//       $id = 1;
//       $inspectio = new ReporteEmail($id);
//       $message->to('fgmerinoca@unitru.edu.pe')
//         ->subject("PRUEBA CRUDA", $inspectio);
//     });

//     return response()->json(["status" => "Correo enviado"], 200);
//   } catch (\Exception $e) {
//     return response()->json(["error" => $e->getMessage()], 500);
//   }
// });
Route::get('/sync', [SyncController::class, 'sync']);
Route::post('/login', [AuthController::class, 'login']);
