<?php

use App\Http\Controllers\ActivePauseController;
use App\Http\Controllers\AlcoholTestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckPointController;
use App\Http\Controllers\DailyDialogController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\EnterpriseTypeController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\GPSControlController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InspectionTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductEnterpriseController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoadTypeController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\TargetedController;
use App\Http\Controllers\TargetedRelsInspectionController;
use App\Http\Controllers\UnitMovementController;
use App\Http\Controllers\UnitTypeController;
use App\Http\Controllers\AlcoholTestDetailController;
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

Auth::routes(['register' => false, 'reset' => false]);


Route::group(['middleware' => 'auth'], function () {

  Route::get('/', [HomeController::class, 'index']);

  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

  Route::get('/checkpoints', [CheckPointController::class, 'index'])->name('checkpoint');
  Route::post('/checkpoints', [CheckPointController::class, 'store'])->name('checkpoint.store');
  Route::get('/checkpoints/{checkpoint}', [CheckPointController::class, 'show'])->name('checkpoint.show');
  Route::get('/checkpoints/{checkpoint}/edit', [CheckPointController::class, 'edit'])->name('checkpoint.edit');
  Route::put('/checkpoints/{checkpoint}', [CheckPointController::class, 'update'])->name('checkpoint.update');
  Route::delete('/checkpoints/{checkpoint}', [CheckPointController::class, 'destroy'])->name('checkpoint.destroy');

  Route::get('/enterprisetypes', [EnterpriseTypeController::class, 'index'])->name('enterprisetype');
  Route::post('/enterprisetypes', [EnterpriseTypeController::class, 'store'])->name('enterprisetype.store');
  Route::get('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'show'])->name('enterprisetype.show');
  Route::get('/enterprisetypes/{enterpriseType}/edit', [EnterpriseTypeController::class, 'edit'])->name('enterprisetype.edit');
  Route::put('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'update'])->name('enterprisetype.update');
  Route::delete('/enterprisetypes/{enterpriseType}', [EnterpriseTypeController::class, 'destroy'])->name('enterprisetype.destroy');

  Route::get('/inspectiontypes', [InspectionTypeController::class, 'index'])->name('inspectiontype');
  Route::post('/inspectiontypes', [InspectionTypeController::class, 'store'])->name('inspectiontype.store');
  Route::get('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'show'])->name('inspectiontype.show');
  Route::get('/inspectiontypes/{inspectionType}/edit', [InspectionTypeController::class, 'edit'])->name('inspectiontype.edit');
  Route::put('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'update'])->name('inspectiontype.update');
  Route::delete('/inspectiontypes/{inspectionType}', [InspectionTypeController::class, 'destroy'])->name('inspectiontype.destroy');

  Route::get('/enterprises', [EnterpriseController::class, 'index'])->name('enterprise');
  Route::post('/enterprises', [EnterpriseController::class, 'store'])->name('enterprise.store');
  Route::get('/enterprises/{enterprise}', [EnterpriseController::class, 'show'])->name('enterprise.show');
  Route::get('/enterprises/{enterprise}/edit', [EnterpriseController::class, 'edit'])->name('enterprise.edit');
  Route::put('/enterprises/{enterprise}', [EnterpriseController::class, 'update'])->name('enterprise.update');
  Route::delete('/enterprises/{enterprise}', [EnterpriseController::class, 'destroy'])->name('enterprise.destroy');
  Route::post('/enterprises/assign', [EnterpriseController::class, 'assign'])->name('enterprise.assign');
  Route::get('/enterprises/{supplier}/{transport}', [EnterpriseController::class, 'allProductsByEnterprise'])->name('enterprise.products');

  Route::get('/targeteds', [TargetedController::class, 'index'])->name('targeted');
  Route::post('/targeteds', [TargetedController::class, 'store'])->name('targeted.store');
  Route::get('/targeteds/{targeted}', [TargetedController::class, 'show'])->name('targeted.show');
  Route::get('/targeteds/{targeted}/edit', [TargetedController::class, 'edit'])->name('targeted.edit');
  Route::put('/targeteds/{targeted}', [TargetedController::class, 'update'])->name('targeted.update');
  Route::delete('/targeteds/{targeted}', [TargetedController::class, 'destroy'])->name('targeted.destroy');
  Route::get('/targeted', [TargetedController::class, 'index1'])->name('target');

  Route::get('/categories', [CategoryController::class, 'index'])->name('category');
  Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
  Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');
  Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
  Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
  Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
  Route::get('/category', [CategoryController::class, 'index1'])->name('category1');

  Route::get('/evidences', [EvidenceController::class, 'index'])->name('evidences');
  Route::post('/evidences', [EvidenceController::class, 'store'])->name('evidences.store');
  Route::get('/evidences/{evidence}', [EvidenceController::class, 'show'])->name('evidences.show');
  Route::get('/evidences/{evidence}/edit', [EvidenceController::class, 'edit'])->name('evidences.edit');
  Route::put('/evidences/{evidence}', [EvidenceController::class, 'update'])->name('evidences.update');
  Route::delete('/evidences/{evidence}', [EvidenceController::class, 'destroy'])->name('evidences.destroy');

  Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections');
  Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
  Route::post('/inspections/massive', [InspectionController::class, 'storeMany'])->name('inspections.massive');
  Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
  Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
  Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
  Route::delete('/inspections/{inspection}', [InspectionController::class, 'destroy'])->name('inspections.destroy');
  Route::get('/inspections/{inspection}/report', [InspectionController::class, 'report'])->name('inspections.report');

  Route::get('/employees', [EmployeeController::class, 'index'])->name('employee');
  Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
  Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
  Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
  Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
  Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

  Route::get('/dialogues', [DailyDialogController::class, 'index'])->name('dialogues');
  Route::post('/dialogues', [DailyDialogController::class, 'store'])->name('dialogues.store');
  Route::post('/dialogues/massive', [DailyDialogController::class, 'storeMany'])->name('dialogues.massive');
  Route::get('/dialogues/{dialogue}', [DailyDialogController::class, 'show'])->name('dialogues.show');
  Route::get('/dialogues/{dialogue}/edit', [DailyDialogController::class, 'edit'])->name('dialogues.edit');
  Route::put('/dialogues/{dialogue}', [DailyDialogController::class, 'update'])->name('dialogues.update');
  Route::delete('/dialogues/{dialogue}', [DailyDialogController::class, 'destroy'])->name('dialogues.destroy');

  Route::get('/actives', [ActivePauseController::class, 'index'])->name('actives');
  Route::post('/actives', [ActivePauseController::class, 'store'])->name('actives.store');
  Route::post('/actives/massive', [ActivePauseController::class, 'storeMany'])->name('actives.massive');
  Route::get('/actives/{active}', [ActivePauseController::class, 'show'])->name('actives.show');
  Route::get('/actives/{active}/edit', [ActivePauseController::class, 'edit'])->name('actives.edit');
  Route::put('/actives/{active}', [ActivePauseController::class, 'update'])->name('actives.update');
  Route::delete('/actives/{active}', [ActivePauseController::class, 'destroy'])->name('actives.destroy');

  Route::get('/tests', [AlcoholTestController::class, 'index'])->name('tests');
  Route::post('/tests', [AlcoholTestController::class, 'store'])->name('tests.store');
  Route::post('/tests/massive', [AlcoholTestController::class, 'storeMany'])->name('tests.massive');
  Route::get('/tests/{test}', [AlcoholTestController::class, 'show'])->name('tests.show');
  Route::get('/tests/{test}/edit', [AlcoholTestController::class, 'edit'])->name('tests.edit');
  Route::put('/tests/{test}', [AlcoholTestController::class, 'update'])->name('tests.update');
  Route::delete('/tests/{test}', [AlcoholTestController::class, 'destroy'])->name('tests.destroy');
  // Detalle de pruebas
  Route::delete('/tests/details/{alcoholTestDetail}', [AlcoholTestDetailController::class, 'destroy'])->name('tests.details.destroy');

  Route::get('/controls', [GPSControlController::class, 'index'])->name('controls');
  Route::post('/controls', [GPSControlController::class, 'store'])->name('controls.store');
  Route::post('/controls/massive', [GPSControlController::class, 'storeMany'])->name('controls.massive');
  Route::get('/controls/{control}', [GPSControlController::class, 'show'])->name('controls.show');
  Route::get('/controls/{control}/edit', [GPSControlController::class, 'edit'])->name('controls.edit');
  Route::put('/controls/{control}', [GPSControlController::class, 'update'])->name('controls.update');
  Route::delete('/controls/{control}', [GPSControlController::class, 'destroy'])->name('controls.destroy');

  Route::get('/products', [ProductController::class, 'index'])->name('products');
  Route::post('/products', [ProductController::class, 'store'])->name('products.store');
  Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
  Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
  Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
  Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

  Route::get('/productenterprises', [ProductEnterpriseController::class, 'index'])->name('productenterprises');
  Route::post('/productenterprises', [ProductEnterpriseController::class, 'store'])->name('productenterprises.store');
  Route::get('/productenterprises/{productEnterprise}', [ProductEnterpriseController::class, 'show'])->name('productenterprises.show');
  Route::get('/productenterprises/{productEnterprise}/edit', [ProductEnterpriseController::class, 'edit'])->name('productenterprises.edit');
  Route::put('/productenterprises/{productEnterprise}', [ProductEnterpriseController::class, 'update'])->name('productenterprises.update');
  Route::delete('/productenterprises/{productEnterprise}', [ProductEnterpriseController::class, 'destroy'])->name('productenterprises.destroy');
  Route::get('/productenterprises/{id_supplier_enterprises}/{id_transport_enterprises}', [ProductEnterpriseController::class, 'getProductsBySupplierAndTransportEnterprise'])->name('productenterprises.getProductsBySupplierAndTransportEnterprise');

  Route::get('/productstypes/parent/{parent_id}', [ProductTypeController::class, 'findByParentId'])->name('productstypes.parent');

  // Unidades de Medida
  Route::get('/units', [UnitController::class, 'index'])->name('unit');
  Route::post('/units', [UnitController::class, 'store'])->name('unit.store');
  Route::put('/units/{unit}', [UnitController::class, 'update'])->name('unit.update');
  Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('unit.destroy');

  // Empresa del Sistema
  Route::get('/companies', [CompanyController::class, 'index'])->name('company');
  Route::post('/companies', [CompanyController::class, 'store'])->name('company.store');
  Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('company.update');
  Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');

  // Tipos de Unidad de Medida
  Route::get('/unittypes', [UnitTypeController::class, 'index'])->name('unittype');
  Route::post('/unittypes', [UnitTypeController::class, 'store'])->name('unittype.store');
  Route::put('/unittypes/{unitType}', [UnitTypeController::class, 'update'])->name('unittype.update');
  Route::delete('/unittypes/{unitType}', [UnitTypeController::class, 'destroy'])->name('unittype.destroy');

  // Tipos de Carga
  Route::get('/loadtypes', [LoadTypeController::class, 'index'])->name('loadtype');
  Route::post('/loadtypes', [LoadTypeController::class, 'store'])->name('loadtype.store');
  Route::put('/loadtypes/{loadType}', [LoadTypeController::class, 'update'])->name('loadtype.update');
  Route::delete('/loadtypes/{loadType}', [LoadTypeController::class, 'destroy'])->name('loadtype.destroy');

  // Tipos de Producto (Clases UN)
  Route::get('/producttypes', [ProductTypeController::class, 'index'])->name('producttype');
  Route::post('/producttypes', [ProductTypeController::class, 'store'])->name('producttype.store');
  Route::put('/producttypes/{productType}', [ProductTypeController::class, 'update'])->name('producttype.update');
  Route::delete('/producttypes/{productType}', [ProductTypeController::class, 'destroy'])->name('producttype.destroy');

  // Dirigidos por Tipo de Inspección
  Route::get('/targetedrelsinspe', [TargetedRelsInspectionController::class, 'index'])->name('targetedrelsinspe');
  Route::post('/targetedrelsinspe', [TargetedRelsInspectionController::class, 'store'])->name('targetedrelsinspe.store');
  Route::put('/targetedrelsinspe/{targetedRelsInspection}', [TargetedRelsInspectionController::class, 'update'])->name('targetedrelsinspe.update');
  Route::delete('/targetedrelsinspe/{targetedRelsInspection}', [TargetedRelsInspectionController::class, 'destroy'])->name('targetedrelsinspe.destroy');

  // Seguridad - Permisos
  Route::get('/permissions', [SecurityController::class, 'permissions'])->name('permissions');
  Route::get('/permissions/create', [SecurityController::class, 'createPermission'])->name('permissions.create');
  Route::post('/permissions', [SecurityController::class, 'storePermission'])->name('permissions.store');
  Route::get('/permissions/{permission}/edit', [SecurityController::class, 'editPermission'])->name('permissions.edit');
  Route::put('/permissions/{permission}', [SecurityController::class, 'updatePermission'])->name('permissions.update');
  Route::delete('/permissions/{permission}', [SecurityController::class, 'destroyPermission'])->name('permissions.destroy');

  // Seguridad - Roles
  Route::get('/roles', [SecurityController::class, 'roles'])->name('roles');
  Route::get('/roles/create', [SecurityController::class, 'createRole'])->name('roles.create');
  Route::post('/roles', [SecurityController::class, 'storeRole'])->name('roles.store');
  Route::get('/roles/{role}/edit', [SecurityController::class, 'editRole'])->name('roles.edit');
  Route::put('/roles/{role}', [SecurityController::class, 'updateRole'])->name('roles.update');
  Route::delete('/roles/{role}', [SecurityController::class, 'destroyRole'])->name('roles.destroy');

  // Seguridad - Usuarios
  Route::get('/users', [SecurityController::class, 'users'])->name('users');
  Route::get('/users/create', [SecurityController::class, 'createUser'])->name('users.create');
  Route::post('/users', [SecurityController::class, 'storeUser'])->name('users.store');
  Route::get('/users/{user}', [SecurityController::class, 'showUser'])->name('users.show');
  Route::get('/users/{user}/edit', [SecurityController::class, 'editUser'])->name('users.edit');
  Route::put('/users/{user}', [SecurityController::class, 'updateUser'])->name('users.update');
  Route::delete('/users/{user}', [SecurityController::class, 'destroyUser'])->name('users.destroy');

  Route::get('/unitmovements', [UnitMovementController::class, 'index'])->name('unitmovements');
  Route::get('/unitmovements/create', [UnitMovementController::class, 'create'])->name('unitmovements.create');
  Route::post('/unitmovements', [UnitMovementController::class, 'store'])->name('unitmovements.store');
  Route::get('/unitmovements/{unitMovement}', [UnitMovementController::class, 'show'])->name('unitmovements.show');
  Route::get('/unitmovements/{unitMovement}/edit', [UnitMovementController::class, 'edit'])->name('unitmovements.edit');
  Route::put('/unitmovements/{unitMovement}', [UnitMovementController::class, 'update'])->name('unitmovements.update');
  Route::delete('/unitmovements/{unitMovement}', [UnitMovementController::class, 'destroy'])->name('unitmovements.destroy');
  Route::get('/unitmovements/export/pdf', [UnitMovementController::class, 'exportPdf'])->name('unitmovements.export.pdf');

  Route::get('/report/daily', [ReportController::class, 'daily'])->name('report.daily');
  Route::get('/report/daily/pdf', [ReportController::class, 'dailyPdf'])->name('report.daily.pdf');
});
