<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ReporteEmail;
use App\Models\ActivePause;
use App\Models\AlcoholTest;
use App\Models\Category;
use App\Models\CheckPoint;
use App\Models\DailyDialog;
use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\EnterpriseType;
use App\Models\ErrorLog;
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
use App\Models\TargetedRelsLoadType;
use App\Models\UnitType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SyncController extends Controller
{

  /**
   * Create a new SyncController instance.
   *
   * @return void
   */
  public function __construct()
  {
    // Middleware se aplica a nivel de rutas (jwt.verify)
  }

  // ─── Helpers privados ────────────────────────────────────────

  /**
   * Parsea una fecha en formato d/m/Y a Y-m-d.
   * Lanza InvalidArgumentException si el formato es inválido.
   */
  private function parseDate(string $dateString, string $format = 'd/m/Y'): string
  {
    $date = Carbon::createFromFormat($format, $dateString);
    if (!$date || $date->format($format) !== $dateString) {
      throw new \InvalidArgumentException("Formato de fecha inválido: '{$dateString}', se esperaba '{$format}'");
    }
    return $date->format('Y-m-d');
  }

  /**
   * Registra un error en la tabla error_logs de forma consistente.
   */
  private function logError(string $type, string $source, \Exception $e, $requestData = null): void
  {
    try {
      ErrorLog::create([
        'date' => Carbon::now('America/Lima')->format('Y-m-d H:i:s'),
        'type' => $type,
        'source' => "SyncController@{$source}",
        'message' => "Línea {$e->getLine()} en {$e->getFile()} — {$e->getMessage()}",
        'trace' => $e->getTraceAsString(),
        'data' => $requestData ? json_encode($requestData) : null,
      ]);
    } catch (\Exception $logEx) {
      Log::error("Error al guardar en ErrorLog: {$logEx->getMessage()} | Error original: {$e->getMessage()}");
    }
  }

  /**
   * Valida que los campos requeridos existan en el array.
   * Lanza InvalidArgumentException si faltan campos.
   */
  private function validateRequired(array $data, array $requiredFields, string $context): void
  {
    $missing = [];
    foreach ($requiredFields as $field) {
      if (!array_key_exists($field, $data) || $data[$field] === null) {
        $missing[] = $field;
      }
    }
    if (!empty($missing)) {
      throw new \InvalidArgumentException("Campos requeridos faltantes en {$context}: " . implode(', ', $missing));
    }
  }

  // ─── Sync (público) ──────────────────────────────────────────

  /**
   * Sincroniza los datos de la aplicación móvil.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sync()
  {
    $inspectionsType = InspectionType::select('id_inspection_types as id', 'name', 'description')->get();
    $enterprisesType = EnterpriseType::select('id_enterprise_types as id', 'name', 'description')->get();
    $enterprises = Enterprise::select('id_enterprises as id', 'name', 'ruc', 'email', 'phone', 'address', 'contact_name', 'website', 'image', 'id_enterprise_types as enterprise_type_id')->get();
    $enterprisesRelsEnterprise = EnterpriseRelsEnterprise::select('id_enterprise_rels_enterprises as id', 'id_supplier_enterprises as supplier_enterprise_id', 'id_transport_enterprises as transport_enterprise_id')->get();
    $categoryShape = fn ($c) => [
      'id' => $c->id_categories,
      'name' => $c->name,
      'parent_id' => $c->parent_id,
      'targeted_id' => optional($c->effectivePair())->id_targeteds,
      'inspection_type_id' => optional($c->effectivePair())->id_inspection_types,
    ];

    $categories = Category::with('targetedRelsInspection')
      ->whereNull('parent_id')->get()->map($categoryShape);

    $subcategories = Category::with('parent.targetedRelsInspection')
      ->whereNotNull('parent_id')->get()->map($categoryShape);
    $targeteds = Targeted::select('id_targeteds as id', 'name', 'image', 'targeted_id')->get();
    $evidences = Evidence::with('subcategory:id_categories,parent_id')
      ->get()
      ->map(fn ($e) => [
        'id' => $e->id_evidences,
        'name' => $e->name,
        'description' => $e->description,
        'category_id' => optional($e->subcategory)->parent_id,
        'subcategory_id' => $e->id_subcategories,
      ]);
    $checkPoints = CheckPoint::select('id_checkpoints as id', 'name', 'description')->get();
    $targetedsRelsInspections = TargetedRelsInspection::select('id_targeted_rels_inspections as id', 'id_targeteds as targeted_id', 'id_inspection_types as inspection_type_id')->get();
    $targetedsRelsLoadTypes = TargetedRelsLoadType::select('id_targeted_rels_load_types as id', 'id_targeteds as targeted_id', 'id_load_types as load_type_id')->get();
    $employees = Employee::select('id_employees as id', 'document', 'name', 'lastname', 'fullname', 'id_transport_enterprises as transport_enterprise_id', 'id_targeteds as targeted_id', 'job_title')->get();
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
      'targetedRelsLoadTypes' => $targetedsRelsLoadTypes,
      'checkPoints' => $checkPoints,
      'employees' => $employees,
      'unitsType' => $unitsType,
      'productsType' => $productsType,
      'products' => $products,
      'productsRelsEnterprise' => $productsRelsEnterprise,
    ], 200);
  }

  // ─── Inspection ──────────────────────────────────────────────

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

      $inspectionRaw = $request->input('inspection');
      $evidencesRaw = $request->input('evidences');
      $convoyRaw = $request->input('convoy');
      $userRaw = $request->input('user');

      if ($inspectionRaw == null) {
        ErrorLog::create([
          'date' => Carbon::now('America/Lima')->format('Y-m-d H:i:s'),
          'type' => 'SyncController - inspection',
          'source' => 'Mobile App',
          'message' => 'Inspeccion y evidencias son requeridas',
          'trace' => null,
          'data' => json_encode($request->all()),
        ]);
        return response()->json([
          'status' => false,
          'message' => 'Inspeccion y evidencias son requeridas',
        ], 400);
      }

      $inspection = json_decode($inspectionRaw, true);
      $user = json_decode($userRaw, true);

      if (!is_array($inspection) || !is_array($user)) {
        return response()->json([
          'status' => false,
          'message' => 'Formato JSON inválido en inspección o usuario',
        ], 400);
      }

      $this->validateRequired($inspection, [
        'date', 'hour', 'inspection_type_id', 'supplier_enterprise_id',
        'transport_enterprise_id', 'checkpoint_id', 'targeted_id',
      ], 'inspección');
      $this->validateRequired($user, ['id'], 'usuario');

      $newInspection = new Inspection();
      $newInspection->date = $this->parseDate($inspection['date']);
      $newInspection->hour = $inspection['hour'];
      $newInspection->id_inspection_types = $inspection['inspection_type_id'];
      $newInspection->id_supplier_enterprises = $inspection['supplier_enterprise_id'];
      $newInspection->id_transport_enterprises = $inspection['transport_enterprise_id'];
      $newInspection->id_checkpoints = $inspection['checkpoint_id'];
      $newInspection->id_targeteds = $inspection['targeted_id'];
      $newInspection->observation = $inspection['observation'] ?? null;
      $newInspection->id_users = $user['id'];
      $newInspection->save();

      if ($convoyRaw != null) {
        $convoy = json_decode($convoyRaw, true);
        if (is_array($convoy)) {
          $newInspectionConvoy = new InspectionConvoy();
          $newInspectionConvoy->id_inspections = $newInspection->id_inspections;
          $newInspectionConvoy->convoy = $convoy['convoy'];
          $newInspectionConvoy->convoy_status = $convoy['convoy_status'];
          $newInspectionConvoy->quantity_light_units = $convoy['quantity_light_units'];
          $newInspectionConvoy->quantity_heavy_units = $convoy['quantity_heavy_units'];
          $newInspectionConvoy->id_products = ($convoy['product_id'] ?? 0) == 0 ? null : $convoy['product_id'];
          $newInspectionConvoy->id_products_two = ($convoy['product_two_id'] ?? 0) == 0 ? null : $convoy['product_two_id'];
          $newInspectionConvoy->save();
        }
      }

      $skippedEvidences = 0;
      $evidencesList = is_array($evidencesRaw) ? $evidencesRaw : [];
      foreach ($evidencesList as $evidenceRaw) {
        $evidence = json_decode($evidenceRaw, true);
        if (!is_array($evidence) || !isset($evidence['evidence_id'], $evidence['state'])) {
          $skippedEvidences++;
          continue;
        }
        $evidenceOne = $this->saveImageBase64($evidence['evidence_one_base64'] ?? null, 'inspection', 'evidence_one');
        $evidenceTwo = $this->saveImageBase64($evidence['evidence_two_base64'] ?? null, 'inspection', 'evidence_two');

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

      DB::commit();

      // Envío de correo (no bloquea la respuesta)
      try {
        Mail::to(config('app.report_email', 'admin@example.com'))
          ->send(new ReporteEmail($newInspection->id_inspections));
      } catch (Exception $e) {
        Log::error('Error al enviar el correo: ' . $e->getMessage());
      }

      $response = [
        'status' => true,
        'message' => 'Inspección creada con éxito',
        'data' => ['id' => $newInspection->id_inspections],
      ];
      if ($skippedEvidences > 0) {
        $response['warnings'] = ["Se omitieron {$skippedEvidences} evidencia(s) por datos incompletos"];
      }
      return response()->json($response, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create Inspection', 'inspection', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Ocurrió un error al crear la inspección',
      ], 500);
    }
  }

  // ─── Daily Dialog ────────────────────────────────────────────

  /**
   * Crea un nuevo dialogo diario.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function dailyDialog(Request $request)
  {
    $request->validate([
      'dialogue' => 'required|string',
      'user' => 'required|string',
    ]);

    try {
      DB::beginTransaction();

      $dialogue = json_decode($request->input('dialogue'), true);
      $user = json_decode($request->input('user'), true);

      if (!is_array($dialogue) || !is_array($user)) {
        return response()->json([
          'status' => false,
          'message' => 'Formato JSON inválido en diálogo o usuario',
        ], 400);
      }

      $this->validateRequired($dialogue, [
        'date', 'hour', 'checkpoint_id', 'supplier_enterprise_id',
        'transport_enterprise_id', 'topic', 'participants',
      ], 'diálogo');
      $this->validateRequired($user, ['id'], 'usuario');

      $newDialogue = new DailyDialog();
      $newDialogue->date = $this->parseDate($dialogue['date']);
      $newDialogue->hour = $dialogue['hour'];
      $newDialogue->id_checkpoints = $dialogue['checkpoint_id'];
      $newDialogue->id_supplier_enterprises = $dialogue['supplier_enterprise_id'];
      $newDialogue->id_transport_enterprises = $dialogue['transport_enterprise_id'];
      $newDialogue->topic = $dialogue['topic'];
      $newDialogue->participants = $dialogue['participants'];
      $newDialogue->photo_one = $this->saveImageBase64($dialogue['photo_one_base64'] ?? null, 'dailyDialog', 'photo_one');
      $newDialogue->photo_two = $this->saveImageBase64($dialogue['photo_two_base64'] ?? null, 'dailyDialog', 'photo_two');
      $newDialogue->id_users = $user['id'];
      $newDialogue->save();

      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Dialogo diario creado con éxito',
        'data' => ['id' => $newDialogue->id_daily_dialogs],
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create Daily Dialog', 'dailyDialog', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Dialogo diario',
      ], 500);
    }
  }

  // ─── Active Pause ────────────────────────────────────────────

  /**
   *  Crea una nueva pausa activa.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function activePause(Request $request)
  {
    $request->validate([
      'pauseactive' => 'required|string',
      'user' => 'required|string',
    ]);

    try {
      DB::beginTransaction();

      $pauseactive = json_decode($request->input('pauseactive'), true);
      $user = json_decode($request->input('user'), true);

      if (!is_array($pauseactive) || !is_array($user)) {
        return response()->json([
          'status' => false,
          'message' => 'Formato JSON inválido en pausa activa o usuario',
        ], 400);
      }

      $this->validateRequired($pauseactive, [
        'date', 'hour', 'checkpoint_id', 'supplier_enterprise_id',
        'transport_enterprise_id', 'participants',
      ], 'pausa activa');
      $this->validateRequired($user, ['id'], 'usuario');

      $newPauseActive = new ActivePause();
      $newPauseActive->date = $this->parseDate($pauseactive['date']);
      $newPauseActive->hour = $pauseactive['hour'];
      $newPauseActive->id_checkpoints = $pauseactive['checkpoint_id'];
      $newPauseActive->id_supplier_enterprises = $pauseactive['supplier_enterprise_id'];
      $newPauseActive->id_transport_enterprises = $pauseactive['transport_enterprise_id'];
      $newPauseActive->participants = $pauseactive['participants'];
      $newPauseActive->photo_one = $this->saveImageBase64($pauseactive['photo_one_base64'] ?? null, 'activePause', 'photo_one');
      $newPauseActive->photo_two = $this->saveImageBase64($pauseactive['photo_two_base64'] ?? null, 'activePause', 'photo_two');
      $newPauseActive->id_users = $user['id'];
      $newPauseActive->save();

      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Pausa Activa creado con éxito',
        'data' => ['id' => $newPauseActive->id_active_pauses],
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create Active Pause', 'activePause', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Error al crear la Pausa Activa',
      ], 500);
    }
  }

  // ─── Alcohol Test ────────────────────────────────────────────

  /**
   * Crea una nueva prueba de alcohol.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function alcoholTest(Request $request)
  {
    $request->validate([
      'alcoholtest' => 'required|string',
      'user' => 'required|string',
    ]);

    try {
      DB::beginTransaction();

      $test = json_decode($request->input('alcoholtest'), true);
      $user = json_decode($request->input('user'), true);

      if (!is_array($test) || !is_array($user)) {
        return response()->json([
          'status' => false,
          'message' => 'Formato JSON inválido en prueba de alcohol o usuario',
        ], 400);
      }

      $this->validateRequired($test, [
        'date', 'hour', 'checkpoint_id', 'supplier_enterprise_id',
        'transport_enterprise_id',
      ], 'prueba de alcohol');
      $this->validateRequired($user, ['id'], 'usuario');

      $newTest = new AlcoholTest();
      $newTest->date = $this->parseDate($test['date']);
      $newTest->hour = $test['hour'];
      $newTest->id_checkpoints = $test['checkpoint_id'];
      $newTest->id_supplier_enterprises = $test['supplier_enterprise_id'];
      $newTest->id_transport_enterprises = $test['transport_enterprise_id'];
      $newTest->id_users = $user['id'];
      $newTest->save();

      // Guardar detalles si vienen en el request
      $skippedDetails = 0;
      $savedDetails = 0;
      if (isset($test['details']) && is_array($test['details'])) {
        foreach ($test['details'] as $detail) {
          if (is_string($detail)) {
            $detail = json_decode($detail, true);
          }

          if (!is_array($detail) || !isset($detail['employee_id'])) {
            Log::warning('Detalle omitido por datos faltantes: ' . json_encode($detail));
            $skippedDetails++;
            continue;
          }

          $detailPhotoOne = $this->saveImageBase64($detail['photo_one_base64'] ?? null, 'alcoholTest', 'detail_photo_one');
          $detailPhotoTwo = $this->saveImageBase64($detail['photo_two_base64'] ?? null, 'alcoholTest', 'detail_photo_two');

          $newDetail = new \App\Models\AlcoholTestDetail();
          $newDetail->id_alcohol_tests = $newTest->id_alcohol_tests;
          $newDetail->id_employees = $detail['employee_id'];
          $newDetail->result = $detail['result'] ?? null;
          $newDetail->state = $detail['state'] ?? null;
          $newDetail->photo_one = $detailPhotoOne;
          $newDetail->photo_one_uri = $detail['photo_one'] ?? null;
          $newDetail->photo_two = $detailPhotoTwo;
          $newDetail->photo_two_uri = $detail['photo_two'] ?? null;
          $newDetail->save();
          $savedDetails++;
        }
      }

      DB::commit();

      $response = [
        'status' => true,
        'message' => 'Prueba de Alcohol creado con éxito',
        'data' => [
          'id' => $newTest->id_alcohol_tests,
          'saved_details' => $savedDetails,
        ],
      ];
      if ($skippedDetails > 0) {
        $response['warnings'] = ["Se omitieron {$skippedDetails} detalle(s) por datos incompletos"];
      }
      return response()->json($response, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create Alcohol Test', 'alcoholTest', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Error al crear la Prueba de Alcohol',
      ], 500);
    }
  }

  // ─── GPS Control ─────────────────────────────────────────────

  /**
   * Crea un nuevo control GPS.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function controlGps(Request $request)
  {
    $request->validate([
      'controlgps' => 'required|string',
      'user' => 'required|string',
    ]);

    try {
      DB::beginTransaction();

      $cgps = json_decode($request->input('controlgps'), true);
      $user = json_decode($request->input('user'), true);

      if (!is_array($cgps) || !is_array($user)) {
        return response()->json([
          'status' => false,
          'message' => 'Formato JSON inválido en control GPS o usuario',
        ], 400);
      }

      $this->validateRequired($cgps, [
        'date', 'hour', 'checkpoint_id', 'supplier_enterprise_id',
        'transport_enterprise_id', 'option', 'state', 'observation',
      ], 'control GPS');
      $this->validateRequired($user, ['id'], 'usuario');

      $newGPS = new GPSControl();
      $newGPS->date = $this->parseDate($cgps['date']);
      $newGPS->hour = $cgps['hour'];
      $newGPS->id_checkpoints = $cgps['checkpoint_id'];
      $newGPS->id_supplier_enterprises = $cgps['supplier_enterprise_id'];
      $newGPS->id_transport_enterprises = $cgps['transport_enterprise_id'];
      $newGPS->option = $cgps['option'];
      $newGPS->state = $cgps['state'];
      $newGPS->observation = $cgps['observation'];
      $newGPS->photo_one = $this->saveImageBase64($cgps['photo_one_base64'] ?? null, 'controlGps', 'photo_one');
      $newGPS->photo_two = $this->saveImageBase64($cgps['photo_two_base64'] ?? null, 'controlGps', 'photo_two');
      $newGPS->id_users = $user['id'];
      $newGPS->save();

      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Control GPS creado con éxito',
        'data' => ['id' => $newGPS->id_gps_controls],
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create GPS Control', 'controlGps', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Control GPS',
      ], 500);
    }
  }

  // ─── Inspection Massive ──────────────────────────────────────

  /**
   * Crea múltiples inspecciones en una sola petición.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function inspectionMassive(Request $request)
  {
    try {
      DB::beginTransaction();

      $inspectionsRaw = $request->input('inspections');
      if ($inspectionsRaw == null) {
        return response()->json([
          'status' => false,
          'message' => 'Inspecciones son requeridas',
        ], 400);
      }

      $inspections = json_decode($inspectionsRaw, true);
      if (!is_array($inspections) || count($inspections) === 0) {
        return response()->json([
          'status' => false,
          'message' => 'El listado de inspecciones está vacío',
        ], 400);
      }

      $createdIds = [];
      $skippedInspections = 0;
      $totalSkippedEvidences = 0;

      foreach ($inspections as $index => $item) {
        $inspection = $item['inspection'] ?? null;
        $evidences = $item['evidences'] ?? [];
        $convoy = $item['convoy'] ?? null;

        if ($inspection == null) {
          $skippedInspections++;
          Log::warning("inspectionMassive: inspección #{$index} omitida — datos nulos");
          continue;
        }

        $this->validateRequired($inspection, [
          'date', 'hour', 'inspection_type_id', 'supplier_enterprise_id',
          'transport_enterprise_id', 'checkpoint_id', 'targeted_id',
        ], "inspección masiva #{$index}");

        $newInspection = new Inspection();
        $newInspection->date = $this->parseDate($inspection['date']);
        $newInspection->hour = $inspection['hour'];
        $newInspection->id_inspection_types = $inspection['inspection_type_id'];
        $newInspection->id_supplier_enterprises = $inspection['supplier_enterprise_id'];
        $newInspection->id_transport_enterprises = $inspection['transport_enterprise_id'];
        $newInspection->id_checkpoints = $inspection['checkpoint_id'];
        $newInspection->id_targeteds = $inspection['targeted_id'];
        $newInspection->observation = $inspection['observation'] ?? '';
        $newInspection->id_users = $inspection['user_id'] ?? null;
        $newInspection->save();

        if ($convoy != null && is_array($convoy)) {
          $newInspectionConvoy = new InspectionConvoy();
          $newInspectionConvoy->id_inspections = $newInspection->id_inspections;
          $newInspectionConvoy->convoy = $convoy['convoy'];
          $newInspectionConvoy->convoy_status = $convoy['convoy_status'];
          $newInspectionConvoy->quantity_light_units = $convoy['quantity_light_units'];
          $newInspectionConvoy->quantity_heavy_units = $convoy['quantity_heavy_units'];
          $newInspectionConvoy->id_products = ($convoy['product_id'] ?? 0) == 0 ? null : $convoy['product_id'];
          $newInspectionConvoy->id_products_two = ($convoy['product_two_id'] ?? 0) == 0 ? null : $convoy['product_two_id'];
          $newInspectionConvoy->save();
        }

        foreach ($evidences as $evidence) {
          if (!isset($evidence['evidence_id'], $evidence['state'])) {
            $totalSkippedEvidences++;
            continue;
          }
          $evidenceOne = $this->saveImageBase64($evidence['evidence_one_base64'] ?? null, 'inspection', 'evidence_one');
          $evidenceTwo = $this->saveImageBase64($evidence['evidence_two_base64'] ?? null, 'inspection', 'evidence_two');

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

        $createdIds[] = $newInspection->id_inspections;

        // Enviar correo por cada inspección
        try {
          Mail::to(config('app.report_email', 'admin@example.com'))
            ->send(new ReporteEmail($newInspection->id_inspections));
        } catch (Exception $e) {
          Log::error('Error al enviar correo para inspección masiva: ' . $e->getMessage());
        }
      }

      DB::commit();

      $response = [
        'status' => true,
        'message' => 'Inspecciones creadas con éxito',
        'data' => [
          'created_ids' => $createdIds,
          'created_count' => count($createdIds),
        ],
      ];
      $warnings = [];
      if ($skippedInspections > 0) {
        $warnings[] = "Se omitieron {$skippedInspections} inspección(es) por datos nulos";
      }
      if ($totalSkippedEvidences > 0) {
        $warnings[] = "Se omitieron {$totalSkippedEvidences} evidencia(s) por datos incompletos";
      }
      if (!empty($warnings)) {
        $response['warnings'] = $warnings;
      }
      return response()->json($response, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->logError('Create Inspection Massive', 'inspectionMassive', $e, $request->all());
      return response()->json([
        'status' => false,
        'message' => 'Ocurrió un error al crear las inspecciones',
      ], 500);
    }
  }

  // ─── Image Helper ────────────────────────────────────────────

  /**
   * Decodifica y guarda la imagen en base64 en public/${path}.
   *
   * @param string|null $base64Image Imagen en formato base64.
   * @param string $path Ruta donde se guardará la imagen.
   * @param string $type Tipo de imagen (para nombrar el archivo).
   * @return string|null Ruta de la imagen guardada o null si no se guarda.
   */
  private function saveImageBase64(?string $base64Image, string $path, string $type): ?string
  {
    if (!$base64Image) {
      return null;
    }

    try {
      // Eliminar prefijo data URI (soporta MIME con caracteres especiales)
      $base64Image = preg_replace('/^data:image\/[a-zA-Z0-9+.-]+;base64,/', '', $base64Image);
      $base64Image = str_replace(' ', '+', $base64Image);
      $imageData = base64_decode($base64Image, true);

      if ($imageData === false) {
        Log::warning("saveImageBase64: base64_decode falló para tipo '{$type}' en ruta '{$path}'");
        return null;
      }

      // Validar tamaño máximo (10MB)
      $maxSize = 10 * 1024 * 1024;
      if (strlen($imageData) > $maxSize) {
        Log::warning("saveImageBase64: imagen excede 10MB para tipo '{$type}' en ruta '{$path}'");
        return null;
      }

      // Validar que sea una imagen válida
      $imageInfo = @getimagesizefromstring($imageData);
      if ($imageInfo === false) {
        Log::warning("saveImageBase64: datos no son una imagen válida para tipo '{$type}' en ruta '{$path}'");
        return null;
      }

      // Validar tipos MIME permitidos
      $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      if (!in_array($imageInfo['mime'], $allowedMimes)) {
        Log::warning("saveImageBase64: MIME no permitido '{$imageInfo['mime']}' para tipo '{$type}'");
        return null;
      }

      // Determinar extensión según MIME real
      $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
      ];
      $extension = $extensions[$imageInfo['mime']] ?? 'png';

      $fullPath = public_path($path);
      if (!file_exists($fullPath)) {
        if (!@mkdir($fullPath, 0755, true)) {
          Log::error("saveImageBase64: no se pudo crear directorio '{$fullPath}'");
          return null;
        }
      }

      $fileName = bin2hex(random_bytes(16)) . "_{$type}.{$extension}";
      $filePath = $fullPath . '/' . $fileName;

      $written = file_put_contents($filePath, $imageData);
      if ($written === false) {
        Log::error("saveImageBase64: no se pudo escribir archivo '{$filePath}'");
        return null;
      }

      return $path . '/' . $fileName;
    } catch (\Exception $e) {
      Log::error("saveImageBase64: excepción — {$e->getMessage()}");
      return null;
    }
  }
}
