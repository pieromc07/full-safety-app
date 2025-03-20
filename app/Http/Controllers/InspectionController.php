<?php

namespace App\Http\Controllers;

use App\Models\EvidenceRelsInspection;
use App\Models\Inspection;
use App\Models\InspectionConvoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $type = $request->input('type') ?? 1;
    $inspections = Inspection::where('id_inspection_types', $type)->orderBy('id_inspections', 'desc')->paginate(10);
    return view('inspections.index', compact('inspections'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      DB::beginTransaction(); // Start transaction

      $inspection = $request->input('inspection');
      $evidences = $request->input('evidences');
      $convoy = $request->input('convoy');

      if ($inspection == null || $evidences == null) {
        return response()->json([
          'message' => 'Inspeccion y evidencias son requeridas',
        ], 400);
      }

      $inspection = json_decode($inspection, true);

      $newInspection = new Inspection();
      $newInspection->date = $inspection['date'];
      $newInspection->hour = $inspection['hour'];
      $newInspection->id_inspection_types = $inspection['id_inspection_types'];
      $newInspection->id_supplier_enterprises = $inspection['id_supplier_enterprises'];
      $newInspection->id_transport_enterprises = $inspection['id_transport_enterprises'];
      $newInspection->checkpoint_id = $inspection['checkpoint_id'];
      $newInspection->targeted_id = $inspection['targeted_id'];
      $newInspection->observation = $inspection['observation'];
      $newInspection->user_id = 1;
      $newInspection->save();

      if ($convoy != null) {
        $convoy = json_decode($convoy, true);
        $newInspectionConvoy = new InspectionConvoy();
        $newInspectionConvoy->inspection_id = $newInspection->id;
        $newInspectionConvoy->convoy = $convoy['convoy'];
        $newInspectionConvoy->convoy_status = $convoy['convoy_status'];
        $newInspectionConvoy->quantity_light_units = $convoy['quantity_light_units'];
        $newInspectionConvoy->quantity_heavy_units = $convoy['quantity_heavy_units'];
        $newInspectionConvoy->save();
      }

      foreach ($evidences as $evidence) {
        $evidence = json_decode($evidence, true);
        if (!isset($evidence['evidence_id'], $evidence['state'])) {
          Log::warning('Evidencia omitida debido a datos faltantes: ' . json_encode($evidence));
          continue;
        }
        $evidenceOne = $this->saveEvidenceImage($evidence['evidence_one_base64'], $newInspection->id, 'evidence_one');
        $evidenceTwo = $this->saveEvidenceImage($evidence['evidence_two_base64'], $newInspection->id, 'evidence_two');

        $newEvidence = new EvidenceRelsInspection();
        $newEvidence->inspection_id = $newInspection->id;
        $newEvidence->evidence_id = $evidence['evidence_id'];
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
      return response()->json([
        'status' => false,
        'message' => 'Error creating inspection',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Store many inspections and evidences and convoy.
   */
  public function storeMany(Request $request)
  {
    try {
      DB::beginTransaction(function () use ($request) {

        $inspections =  $request->input('inspections');
        return response()->json([
          'message' => $inspections,
        ], 400);
        if ($inspections == null) {
          return response()->json([
            'message' => 'Inspecciones son requeridas',
          ], 400);
        }
        $inspections = json_decode($inspections, true);
        foreach ($inspections as $inspection) {
          $newInspection = new Inspection();
          $newInspection->date = $inspection['date'];
          $newInspection->hour = $inspection['hour'];
          $newInspection->id_inspection_types = $inspection['id_inspection_types'];
          $newInspection->enterprise_id = $inspection['enterprise_id'];
          $newInspection->checkpoint_id = $inspection['checkpoint_id'];
          $newInspection->targeted_id = $inspection['targeted_id'];
          $newInspection->observation = $inspection['observation'];
          $newInspection->user_id = 1;
          $newInspection->save();

          if (isset($inspection['convoy'])) {
            $convoy = $inspection['convoy'];
            $newInspectionConvoy = new InspectionConvoy();
            $newInspectionConvoy->inspection_id = $newInspection->id;
            $newInspectionConvoy->convoy = $convoy['convoy'];
            $newInspectionConvoy->convoy_status = $convoy['convoy_status'];
            $newInspectionConvoy->quantity_light_units = $convoy['quantity_light_units'];
            $newInspectionConvoy->quantity_heavy_units = $convoy['quantity_heavy_units'];
            $newInspectionConvoy->save();
          }

          $evidences = $inspection['evidences'];
          foreach ($evidences as $evidence) {
            $evidence = json_decode($evidence, true);
            if (!isset($evidence['evidence_id'], $evidence['state'])) {
              Log::warning('Evidencia omitida debido a datos faltantes: ' . json_encode($evidence));
              continue; // O omitir esta evidencia y pasar a la siguiente
            }
            $evidenceOne = $this->saveEvidenceImage($evidence['evidence_one_base64'], $newInspection->id, 'evidence_one');
            $evidenceTwo = $this->saveEvidenceImage($evidence['evidence_two_base64'], $newInspection->id, 'evidence_two');
            $newEvidence = new EvidenceRelsInspection();
            $newEvidence->inspection_id = $newInspection->id;
            $newEvidence->evidence_id = $evidence['evidence_id'];
            $newEvidence->state = $evidence['state'];
            $newEvidence->evidence_one = $evidenceOne;
            $newEvidence->evidence_two = $evidenceTwo;
            $newEvidence->observations = $evidence['observation'] ?? '';
            $newEvidence->waiting_time = $evidence['waiting_time'] ?? 0;
            $newEvidence->save();
          }
        }
      });
      return response()->json([
        'status' => true,
        'message' => 'Inspecciones creadas con éxito',
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error creating inspections',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Decodifica y guarda la imagen en base64 en el disco.
   *
   * @param string|null $base64Image Imagen en formato base64.
   * @param int $inspectionId ID de la inspección asociada.
   * @param string $type Tipo de evidencia (evidence_one, evidence_two, etc.).
   * @return string|null Ruta de la imagen guardada o null si no se guarda.
   */
  private function saveEvidenceImage(?string $base64Image, int $inspectionId, string $type): ?string
  {
    if (!$base64Image) {
      return null;
    }

    try {
      // Eliminar la cabecera del base64
      $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
      $base64Image = str_replace(' ', '+', $base64Image);
      $imageData = base64_decode($base64Image);

      // Generar nombre de archivo único
      $fileName = time() . "_{$inspectionId}_{$type}.png";
      if (Storage::disk('public')->put('evidences/' . $fileName, $imageData)) {
        return 'evidences/' . $fileName;
      }

      return null;
    } catch (\Exception $e) {
      // En caso de error, devolver null
      return null;
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Inspection $inspection)
  {

    return view('inspections.show', compact('inspection'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Inspection $inspection)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Inspection $inspection)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Inspection $inspection)
  {
    //
  }
}
