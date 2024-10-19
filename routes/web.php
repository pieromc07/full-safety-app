<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckPointController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\EnterpriseTypeController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InspectionTypeController;
use App\Http\Controllers\TargetedController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();


Route::group(['middleware' => 'auth'], function () {

  Route::get('/', [HomeController::class, 'index']);

  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

  Route::get('/checkpoints', [CheckPointController::class, 'index'])->name('checkpoint');
  Route::get('/checkpoints/create', [CheckPointController::class, 'create'])->name('checkpoint.create');
  Route::post('/checkpoints', [CheckPointController::class, 'store'])->name('checkpoint.store');
  Route::get('/checkpoints/{checkpoint}', [CheckPointController::class, 'show'])->name('checkpoint.show');
  Route::get('/checkpoints/{checkpoint}/edit', [CheckPointController::class, 'edit'])->name('checkpoint.edit');
  Route::put('/checkpoints/{checkpoint}', [CheckPointController::class, 'update'])->name('checkpoint.update');
  Route::delete('/checkpoints/{checkpoint}', [CheckPointController::class, 'destroy'])->name('checkpoint.destroy');

  Route::get('/enterprisetypes', [EnterpriseTypeController::class, 'index'])->name('enterprisetype');
  Route::get('/enterprisetypes/create', [EnterpriseTypeController::class, 'create'])->name('enterprisetype.create');
  Route::post('/enterprisetypes', [EnterpriseTypeController::class, 'store'])->name('enterprisetype.store');
  Route::get('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'show'])->name('enterprisetype.show');
  Route::get('/enterprisetypes/{enterpriseType}/edit', [EnterpriseTypeController::class, 'edit'])->name('enterprisetype.edit');
  Route::put('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'update'])->name('enterprisetype.update');
  Route::delete('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'destroy'])->name('enterprisetype.destroy');

  Route::get('/inspectiontypes', [InspectionTypeController::class, 'index'])->name('inspectiontype');
  Route::get('/inspectiontypes/create', [InspectionTypeController::class, 'create'])->name('inspectiontype.create');
  Route::post('/inspectiontypes', [InspectionTypeController::class, 'store'])->name('inspectiontype.store');
  Route::get('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'show'])->name('inspectiontype.show');
  Route::get('/inspectiontypes/{inspectionType}/edit', [InspectionTypeController::class, 'edit'])->name('inspectiontype.edit');
  Route::put('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'update'])->name('inspectiontype.update');
  Route::delete('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'destroy'])->name('inspectiontype.destroy');

  Route::get('/enterprises', [EnterpriseController::class, 'index'])->name('enterprise');
  Route::get('/enterprises/create', [EnterpriseController::class, 'create'])->name('enterprise.create');
  Route::post('/enterprises', [EnterpriseController::class, 'store'])->name('enterprise.store');
  Route::get('/enterprises/{enterprise}', [EnterpriseController::class, 'show'])->name('enterprise.show');
  Route::get('/enterprises/{enterprise}/edit', [EnterpriseController::class, 'edit'])->name('enterprise.edit');
  Route::put('/enterprises/{enterprise}', [EnterpriseController::class, 'update'])->name('enterprise.update');
  Route::delete('/enterprises/{enterprise}', [EnterpriseController::class, 'destroy'])->name('enterprise.destroy');
  Route::post('/enterprises/assign', [EnterpriseController::class, 'assign'])->name('enterprise.assign');

  Route::get('/targeteds', [TargetedController::class, 'index'])->name('targeted');
  Route::get('/targeteds/create', [TargetedController::class, 'create'])->name('targeted.create');
  Route::post('/targeteds', [TargetedController::class, 'store'])->name('targeted.store');
  Route::get('/targeteds/{targeted}', [TargetedController::class, 'show'])->name('targeted.show');
  Route::get('/targeteds/{targeted}/edit', [TargetedController::class, 'edit'])->name('targeted.edit');
  Route::put('/targeteds/{targeted}', [TargetedController::class, 'update'])->name('targeted.update');
  Route::delete('/targeteds/{targeted}', [TargetedController::class, 'destroy'])->name('targeted.destroy');
  Route::get('/targeted', [TargetedController::class, 'index1'])->name('target');

  Route::get('/categories', [CategoryController::class, 'index'])->name('category');
  Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
  Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
  Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');
  Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
  Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
  Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
  Route::get('/category', [CategoryController::class, 'index1'])->name('category1');

  Route::get('/evidence', [EvidenceController::class, 'index'])->name('evidence');
  Route::get('/evidence/create', [EvidenceController::class, 'create'])->name('evidence.create');
  Route::post('/evidence', [EvidenceController::class, 'store'])->name('evidence.store');
  Route::get('/evidence/{evidence}', [EvidenceController::class, 'show'])->name('evidence.show');
  Route::get('/evidence/{evidence}/edit', [EvidenceController::class, 'edit'])->name('evidence.edit');
  Route::put('/evidence/{evidence}', [EvidenceController::class, 'update'])->name('evidence.update');
  Route::delete('/evidence/{evidence}', [EvidenceController::class, 'destroy'])->name('evidence.destroy');

  Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections');
  Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
  Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
  Route::post('/inspections/massive', [InspectionController::class, 'storeMany'])->name('inspections.massive');
  Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
  Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
  Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
  Route::delete('/inspections/{inspection}', [InspectionController::class, 'destroy'])->name('inspections.destroy');
});
