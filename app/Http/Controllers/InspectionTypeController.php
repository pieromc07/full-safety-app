<?php

namespace App\Http\Controllers;

use App\Models\InspectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionTypeController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {

    $inspectionTypes = InspectionType::paginate(10);
    return view($this::$viewDir . '.inspectiontype', compact('inspectionTypes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(InspectionType::$rules, InspectionType::$rulesMessages);
    try {
      DB::beginTransaction();
      $inspectionType = new InspectionType();
      $inspectionType->fill($validated);
      $inspectionType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('inspectiontype')->with('error', 'Ha ocurrido un error al intentar crear el tipo de inspección.');
    }
    return redirect()->route('inspectiontype')->with('success', 'El tipo de inspección se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(InspectionType $inspectionType)
  {
    return view($this::$viewDir . '.inspectiontype', compact('inspectionType'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(InspectionType $inspectionType)
  {
    return view($this::$viewDir . '.inspectiontype', compact('inspectionType'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, InspectionType $inspectionType)
  {
    $validated = $request->validate(InspectionType::$rules, InspectionType::$rulesMessages);
    try {
      DB::beginTransaction();
      $inspectionType->fill($validated);
      $inspectionType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('inspectiontype')->with('error', 'Ha ocurrido un error al intentar actualizar el tipo de inspección.');
    }
    return redirect()->route('inspectiontype')->with('success', 'El tipo de inspección se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(InspectionType $inspectionType)
  {
    try {
      DB::beginTransaction();
      $inspCount = \App\Models\Inspection::where('id_inspection_types', $inspectionType->id_inspection_types)->count();
      if ($inspCount > 0) {
        return redirect()->route('inspectiontype')->with('error', 'No se puede eliminar el tipo de inspección porque tiene inspecciones asociadas.');
      }
      $relCount = \App\Models\TargetedRelsInspection::where('id_inspection_types', $inspectionType->id_inspection_types)->count();
      if ($relCount > 0) {
        return redirect()->route('inspectiontype')->with('error', 'No se puede eliminar el tipo de inspección porque tiene dirigidos asociados.');
      }
      $this::softDelete($inspectionType);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('inspectiontype')->with('error', 'Ha ocurrido un error al intentar eliminar el tipo de inspección.');
    }
    return redirect()->route('inspectiontype')->with('success', 'El tipo de inspección se ha eliminado correctamente.');
  }
}
