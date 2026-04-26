<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitTypeController extends Controller
{
  static $viewDir = 'maintenance';

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $unitTypes = UnitType::whereNull('cuid_deleted')->paginate(10);
    return view($this::$viewDir . '.unittype', compact('unitTypes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(UnitType::$rules, UnitType::$rulesMessages);
    try {
      DB::beginTransaction();
      $unitType = new UnitType();
      $unitType->fill($validated);
      $unitType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unittype')->with('error', 'Ha ocurrido un error al intentar crear el tipo de unidad.');
    }
    return redirect()->route('unittype')->with('success', 'El tipo de unidad se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, UnitType $unitType)
  {
    $validated = $request->validate(UnitType::$rules, UnitType::$rulesMessages);
    try {
      DB::beginTransaction();
      $unitType->fill($validated);
      $unitType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unittype')->with('error', 'Ha ocurrido un error al intentar actualizar el tipo de unidad.');
    }
    return redirect()->route('unittype')->with('success', 'El tipo de unidad se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(UnitType $unitType)
  {
    try {
      DB::beginTransaction();
      if ($unitType->products()->whereNull('cuid_deleted')->count() > 0) {
        return redirect()->route('unittype')->with('error', 'No se puede eliminar el tipo de unidad porque tiene productos asociados.');
      }
      $this::softDelete($unitType);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unittype')->with('error', 'Ha ocurrido un error al intentar eliminar el tipo de unidad.');
    }
    return redirect()->route('unittype')->with('success', 'El tipo de unidad se ha eliminado correctamente.');
  }
}
