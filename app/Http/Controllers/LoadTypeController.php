<?php

namespace App\Http\Controllers;

use App\Models\LoadType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadTypeController extends Controller
{
  static $viewDir = 'maintenance';

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $loadTypes = LoadType::whereNull('cuid_deleted')->paginate(10);
    return view($this::$viewDir . '.loadtype', compact('loadTypes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(LoadType::$rules, LoadType::$rulesMessages);
    try {
      DB::beginTransaction();
      $loadType = new LoadType();
      $loadType->fill($validated);
      $loadType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('loadtype')->with('error', 'Ha ocurrido un error al intentar crear el tipo de carga.');
    }
    return redirect()->route('loadtype')->with('success', 'El tipo de carga se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, LoadType $loadType)
  {
    $validated = $request->validate(LoadType::$rules, LoadType::$rulesMessages);
    try {
      DB::beginTransaction();
      $loadType->fill($validated);
      $loadType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('loadtype')->with('error', 'Ha ocurrido un error al intentar actualizar el tipo de carga.');
    }
    return redirect()->route('loadtype')->with('success', 'El tipo de carga se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(LoadType $loadType)
  {
    try {
      DB::beginTransaction();
      if ($loadType->targetedRelsLoadTypes()->count() > 0) {
        return redirect()->route('loadtype')->with('error', 'No se puede eliminar el tipo de carga porque tiene dirigidos asociados.');
      }
      $this::softDelete($loadType);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('loadtype')->with('error', 'Ha ocurrido un error al intentar eliminar el tipo de carga.');
    }
    return redirect()->route('loadtype')->with('success', 'El tipo de carga se ha eliminado correctamente.');
  }
}
