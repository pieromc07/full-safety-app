<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivePause;
use App\Models\AlcoholTest;
use App\Models\Category;
use App\Models\CheckPoint;
use App\Models\DailyDialog;
use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\EnterpriseType;
use App\Models\Evidence;
use App\Models\EvidenceRelsInspection;
use App\Models\GPSControl;
use App\Models\Inspection;
use App\Models\InspectionConvoy;
use App\Models\InspectionType;
use App\Models\Product;
use App\Models\ProductEnterprise;
use App\Models\ProductType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use App\Models\UnitType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{

  /**
   * Create a new SyncController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['sync']]);
  }

  /**
   * Sincroniza los datos de la aplicación móvil.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sync()
  {
    $inspectionsType = InspectionType::select('id_inspection_types as id', 'name', 'description')->get();
    $enterprisesType = EnterpriseType::select('id_enterprise_types as id', 'name', 'description')->get();
    $enterprises = Enterprise::select('id_enterprises as id', 'name', 'ruc', 'image', 'id_enterprise_types as enterprise_type_id')->get();
    $enterprisesRelsEnterprise = EnterpriseRelsEnterprise::select('id_enterprise_rels_enterprises as id', 'id_supplier_enterprises as supplier_enterprise_id', 'id_transport_enterprises as transport_enterprise_id')->get();
    $categories = Category::select('id_categories as id', 'name', 'parent_id', 'id_targeteds as targeted_id', 'id_inspection_types as inspection_type_id')->where('parent_id', null)->get();
    $subcategories = Category::select('id_categories as id', 'name', 'parent_id', 'id_targeteds as targeted_id', 'id_inspection_types as inspection_type_id')->where('parent_id', '!=', null)->get();
    $targeteds = Targeted::select('id_targeteds as id', 'name', 'image', 'targeted_id')->get();
    $evidences = Evidence::select('id_evidences as id', 'name', 'description', 'id_categories as category_id', 'id_subcategories as subcategory_id')->get();
    $checkPoints = CheckPoint::select('id_checkpoints as id', 'name', 'description')->get();
    $targetedsRelsInspections = TargetedRelsInspection::select('id_targeted_rels_inspections as id', 'id_targeteds as targeted_id', 'id_inspection_types as inspection_type_id')->get();
    $employees = Employee::select('id_employees as id', 'document', 'name', 'lastname', 'fullname', 'id_transport_enterprises as transport_enterprise_id')->get();
    $unitsType = UnitType::select('id_unit_types as id', 'name')->get();
    $productsType = ProductType::select('id_product_types as id', 'code', 'name', 'parent_id')->get();
    $products = Product::select('id_products as id', 'name', 'number_onu', 'health', 'flammability', 'reactivity', 'special', 'id_product_types as product_type_id', 'id_unit_types as unit_type_id')->get();
    $productsRelsEnterprise = ProductEnterprise::select('id_product_enterprises as id', 'id_products as product_id', 'id_supplier_enterprises as supplier_enterprise_id', 'id_transport_enterprises as transport_enterprise_id')->get();
    return response()->json([
      'inspectionsType' => $inspectionsType,
      'enterprisesType' => $enterprisesType,
      'enterprises' => $enterprises,
      'enterprisesRelsEnterprise' => $enterprisesRelsEnterprise,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'evidences' => $evidences,
      'targeteds' => $targeteds,
      'targetedRelsInspections' => $targetedsRelsInspections,
      'checkPoints' => $checkPoints,
      'employees' => $employees,
      'unitsType' => $unitsType,
      'productsType' => $productsType,
      'products' => $products,
      'productsRelsEnterprise' => $productsRelsEnterprise,
    ], 200);
  }

  /**
   * Crea una nueva inspección.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function inspection(Request $request)
  {
    try {
      DB::beginTransaction();

      $inspection = $request->input('inspection');
      $evidences = $request->input('evidences');
      $convoy = $request->input('convoy');
      $user = $request->input('user');

      if ($inspection == null || $evidences == null) {
        return response()->json([
          'message' => 'Inspeccion y evidencias son requeridas',
        ], 400);
      }

      $inspection = json_decode($inspection, true);
      $user = json_decode($user, true);

      $newInspection = new Inspection();
      $newInspection->date = Carbon::createFromFormat('d/m/Y', $inspection['date'])->format('Y-m-d');
      $newInspection->hour = $inspection['hour'];
      $newInspection->id_inspection_types = $inspection['inspection_type_id'];
      $newInspection->id_supplier_enterprises = $inspection['supplier_enterprise_id'];
      $newInspection->id_transport_enterprises = $inspection['transport_enterprise_id'];
      $newInspection->id_checkpoints = $inspection['checkpoint_id'];
      $newInspection->id_targeteds = $inspection['targeted_id'];
      $newInspection->observation = $inspection['observation'];
      $newInspection->id_users = $user['id'];
      $newInspection->save();
      if ($convoy != null) {
        $convoy = json_decode($convoy, true);
        $newInspectionConvoy = new InspectionConvoy();
        $newInspectionConvoy->id_inspections = $newInspection->id_inspections;
        $newInspectionConvoy->convoy = $convoy['convoy'];
        $newInspectionConvoy->convoy_status = $convoy['convoy_status'];
        $newInspectionConvoy->quantity_light_units = $convoy['quantity_light_units'];
        $newInspectionConvoy->quantity_heavy_units = $convoy['quantity_heavy_units'];
        $newInspectionConvoy->id_products = $convoy['product_id'] == 0 ? null : $convoy['product_id'];
        $newInspectionConvoy->id_products_two = $convoy['product_two_id'] == 0 ? null : $convoy['product_two_id'];
        $newInspectionConvoy->save();
      }

      foreach ($evidences as $evidence) {
        $evidence = json_decode($evidence, true);
        if (!isset($evidence['evidence_id'], $evidence['state'])) {
          Log::warning('Evidencia omitida debido a datos faltantes: ' . json_encode($evidence));
          continue;
        }
        $evidenceOne = $this->saveImageBase64($evidence['evidence_one_base64'], 'inspection', 'evidence_one');
        $evidenceTwo = $this->saveImageBase64($evidence['evidence_two_base64'], 'inspection', 'evidence_two');

        $newEvidence = new EvidenceRelsInspection();
        $newEvidence->id_inspections = $newInspection->id_inspections;
        $newEvidence->id_evidences = $evidence['evidence_id'];
        $newEvidence->state = $evidence['state'];
        $newEvidence->evidence_one = $evidenceOne;
        $newEvidence->evidence_two = $evidenceTwo;
        $newEvidence->observations = $evidence['observation'] ?? '';
        $newEvidence->waiting_time = $evidence['waiting_time'] ?? 0;
        $newEvidence->save();
      }

      DB::commit(); // Commit transaction if everything is successful
      return response()->json([
        'status' => true,
        'message' => 'Inspección creada con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      Log::error('Error al crear la inspección: ' . $e->getMessage());
      return response()->json([
        'status' => false,
        'message' => "Ocurrió un error al crear la inspección",
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ], 500);
    }
  }


  /**
   * Crea un nuevo dialogo diario.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function dailyDialog(Request $request)
  {
    try {
      DB::beginTransaction();

      $dialogue = $request->input('dialogue');
      $user = $request->input('user');
      if ($dialogue == null) {
        return response()->json([
          'message' => 'Dialogo diario es requerido',
        ], 400);
      }

      $dialogue = json_decode($dialogue, true);
      $user = json_decode($user, true);

      $newDialogue = new DailyDialog();

      $newDialogue->date = Carbon::createFromFormat('d/m/Y', $dialogue['date'])->format('Y-m-d');
      $newDialogue->hour = $dialogue['hour'];
      $newDialogue->id_checkpoints = $dialogue['checkpoint_id'];
      $newDialogue->id_supplier_enterprises = $dialogue['supplier_enterprise_id'];
      $newDialogue->id_transport_enterprises = $dialogue['transport_enterprise_id'];
      $newDialogue->topic = $dialogue['topic'];
      $newDialogue->participants = $dialogue['participants'];
      $newDialogue->photo_one = $this->saveImageBase64($dialogue['photo_one_base64'], 'dailyDialog', 'photo_one');
      $newDialogue->photo_two = $this->saveImageBase64($dialogue['photo_two_base64'], 'dailyDialog', 'photo_two');
      $newDialogue->id_users = $user['id'];
      $newDialogue->save();

      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Dialogo diario creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Dialogo diario',
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ], 500);
    }
  }


  /**
   *  Crea una nueva pausa activa.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function activePause(Request $request)
  {
    try {
      DB::beginTransaction();

      $pauseactive = $request->input('pauseactive');
      $user = $request->input('user');
      if ($pauseactive == null) {
        return response()->json([
          'message' => 'Pausa Activa es requerido',
        ], 400);
      }

      $pauseactive = json_decode($pauseactive, true);
      $user = json_decode($user, true);

      $newPauseActive = new ActivePause();

      $newPauseActive->date = Carbon::createFromFormat('d/m/Y', $pauseactive['date'])->format('Y-m-d');
      $newPauseActive->hour = $pauseactive['hour'];
      $newPauseActive->id_checkpoints = $pauseactive['checkpoint_id'];
      $newPauseActive->id_supplier_enterprises = $pauseactive['supplier_enterprise_id'];
      $newPauseActive->id_transport_enterprises = $pauseactive['transport_enterprise_id'];
      $newPauseActive->participants = $pauseactive['participants'];
      $newPauseActive->photo_one = $this->saveImageBase64($pauseactive['photo_one_base64'], 'activePause', 'photo_one');
      $newPauseActive->photo_two = $this->saveImageBase64($pauseactive['photo_two_base64'], 'activePause', 'photo_two');
      $newPauseActive->id_users = $user['id'];
      $newPauseActive->save();
      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Pausa Activa creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Pausa Activa',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Crea una nueva prueba de alcohol.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function alcoholTest(Request $request)
  {
    try {
      DB::beginTransaction();

      $test = $request->input('alcoholtest');
      $user = $request->input('user');
      if ($test == null) {
        return response()->json([
          'message' => 'Prueba de Alcohol es requerido',
        ], 400);
      }

      $test = json_decode($test, true);
      $user = json_decode($user, true);
      $newTest = new AlcoholTest();

      $newTest->date = Carbon::createFromFormat('d/m/Y', $test['date'])->format('Y-m-d');
      $newTest->hour = $test['hour'];
      $newTest->id_checkpoints = $test['checkpoint_id'];
      $newTest->id_supplier_enterprises = $test['supplier_enterprise_id'];
      $newTest->id_transport_enterprises = $test['transport_enterprise_id'];
      $newTest->id_employees = $test['employee_id'];
      $newTest->result = $test['result'];
      $newTest->state = $test['state'];
      $newTest->photo_one = $this->saveImageBase64($test['photo_one_base64'], 'alcoholTest', 'photo_one');
      $newTest->photo_two = $this->saveImageBase64($test['photo_two_base64'], 'alcoholTest', 'photo_two');
      $newTest->id_users = $user['id'];
      $newTest->save();
      DB::commit(); // Commit transaction if everything is successful
      return response()->json([
        'status' => true,
        'message' => 'Prueba de Alcohol creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Pausa Activa',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Crea un nuevo control GPS.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function controlGps(Request $request)
  {
    try {
      DB::beginTransaction();

      $cgps = $request->input('controlgps');
      $user = $request->input('user');
      if ($cgps == null) {
        return response()->json([
          'message' => 'Control GPS es requerido',
        ], 400);
      }

      $cgps = json_decode($cgps, true);
      $user = json_decode($user, true);

      $newGPS = new GPSControl();
      $newGPS->date = Carbon::createFromFormat('d/m/Y', $cgps['date'])->format('Y-m-d');
      $newGPS->hour = $cgps['hour'];
      $newGPS->id_checkpoints = $cgps['checkpoint_id'];
      $newGPS->id_supplier_enterprises = $cgps['supplier_enterprise_id'];
      $newGPS->id_transport_enterprises = $cgps['transport_enterprise_id'];
      $newGPS->option = $cgps['option'];
      $newGPS->state = $cgps['state'];
      $newGPS->observation = $cgps['observation'];
      $newGPS->photo_one = $this->saveImageBase64($cgps['photo_one_base64'], 'controlGps', 'photo_one');
      $newGPS->photo_two = $this->saveImageBase64($cgps['photo_two_base64'], 'controlGps', 'photo_two');
      $newGPS->id_users = $user['id'];
      $newGPS->save();
      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Control GPS creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Control GPS',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  /**
   * Decodifica y guarda la imagen en base64 en public/${path}.
   *
   * @param string|null $base64Image Imagen en formato base64.
   * @param string $path Ruta donde se guardará la imagen.
   * @return string|null Ruta de la imagen guardada o null si no se guarda.
   */
  private function saveImageBase64(?string $base64Image, string $path, string $type): ?string
  {
    if (!$base64Image) {
      return null;
    }

    try {

      $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
      $base64Image = str_replace(' ', '+', $base64Image);
      $imageData = base64_decode($base64Image);

      if ($imageData === false) {
        return null;
      }

      $fullPath = public_path($path);
      if (!file_exists($fullPath)) {
        mkdir($fullPath, 0755, true);
      }

      $fileName = time() . "_{$type}.png";
      $filePath = $fullPath . '/' . $fileName;

      if (file_put_contents($filePath, $imageData)) {
        return $path . '/' . $fileName;
      }
    } catch (\Exception $e) {
      Log::error('Error al guardar la imagen: ' . $e->getMessage());
      return null;
    }

    return null;
  }
}
