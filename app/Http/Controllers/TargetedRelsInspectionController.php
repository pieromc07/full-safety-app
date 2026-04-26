<?php

namespace App\Http\Controllers;

use App\Models\InspectionType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetedRelsInspectionController extends Controller
{
  static $viewDir = 'maintenance';

  private $rules = [
    'id_targeteds' => 'required|exists:targeteds,id_targeteds',
    'id_inspection_types' => 'required|exists:inspection_types,id_inspection_types',
  ];

  private $rulesMessages = [
    'id_targeteds.required' => 'El dirigido es obligatorio.',
    'id_targeteds.exists' => 'El dirigido seleccionado no existe.',
    'id_inspection_types.required' => 'El tipo de inspección es obligatorio.',
    'id_inspection_types.exists' => 'El tipo de inspección seleccionado no existe.',
  ];

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $relations = TargetedRelsInspection::with(['targeted', 'inspectionType'])->paginate(10);
    $targeteds = Targeted::whereNull('cuid_deleted')->get();
    $inspectionTypes = InspectionType::whereNull('cuid_deleted')->get();
    return view($this::$viewDir . '.targetedrelsinspe', compact('relations', 'targeteds', 'inspectionTypes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate($this->rules, $this->rulesMessages);

    $exists = TargetedRelsInspection::where('id_targeteds', $validated['id_targeteds'])
      ->where('id_inspection_types', $validated['id_inspection_types'])
      ->first();

    if ($exists) {
      return redirect()->route('targetedrelsinspe')->with('error', 'Esta relación ya existe.');
    }

    try {
      DB::beginTransaction();
      $relation = new TargetedRelsInspection();
      $relation->fill($validated);
      $relation->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('targetedrelsinspe')->with('error', 'Ha ocurrido un error al intentar crear la relación.');
    }
    return redirect()->route('targetedrelsinspe')->with('success', 'La relación se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, TargetedRelsInspection $targetedRelsInspection)
  {
    $validated = $request->validate($this->rules, $this->rulesMessages);

    $exists = TargetedRelsInspection::where('id_targeteds', $validated['id_targeteds'])
      ->where('id_inspection_types', $validated['id_inspection_types'])
      ->where('id_targeted_rels_inspections', '!=', $targetedRelsInspection->id_targeted_rels_inspections)
      ->first();

    if ($exists) {
      return redirect()->route('targetedrelsinspe')->with('error', 'Esta relación ya existe.');
    }

    try {
      DB::beginTransaction();
      $targetedRelsInspection->fill($validated);
      $targetedRelsInspection->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('targetedrelsinspe')->with('error', 'Ha ocurrido un error al intentar actualizar la relación.');
    }
    return redirect()->route('targetedrelsinspe')->with('success', 'La relación se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(TargetedRelsInspection $targetedRelsInspection)
  {
    try {
      DB::beginTransaction();
      $this::softDelete($targetedRelsInspection);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('targetedrelsinspe')->with('error', 'Ha ocurrido un error al intentar eliminar la relación.');
    }
    return redirect()->route('targetedrelsinspe')->with('success', 'La relación se ha eliminado correctamente.');
  }
}
