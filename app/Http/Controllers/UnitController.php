<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
  static $viewDir = 'maintenance';

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $units = Unit::whereNull('cuid_deleted')->paginate(self::MEDIUMTAKE);
    return view($this::$viewDir . '.unit', compact('units'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(Unit::$rules, Unit::$rulesMessages);
    try {
      DB::beginTransaction();
      $unit = new Unit();
      $unit->fill($validated);
      $unit->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unit')->with('error', 'Ha ocurrido un error al intentar crear la unidad de medida.');
    }
    return redirect()->route('unit')->with('success', 'La unidad de medida se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Unit $unit)
  {
    $validated = $request->validate(Unit::$rules, Unit::$rulesMessages);
    try {
      DB::beginTransaction();
      $unit->fill($validated);
      $unit->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unit')->with('error', 'Ha ocurrido un error al intentar actualizar la unidad de medida.');
    }
    return redirect()->route('unit')->with('success', 'La unidad de medida se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Unit $unit)
  {
    try {
      DB::beginTransaction();
      if ($unit->products()->whereNull('cuid_deleted')->count() > 0) {
        return redirect()->route('unit')->with('error', 'No se puede eliminar la unidad porque tiene productos asociados.');
      }
      $this::softDelete($unit);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unit')->with('error', 'Ha ocurrido un error al intentar eliminar la unidad de medida.');
    }
    return redirect()->route('unit')->with('success', 'La unidad de medida se ha eliminado correctamente.');
  }
}
