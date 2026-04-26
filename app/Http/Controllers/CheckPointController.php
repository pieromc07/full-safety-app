<?php

namespace App\Http\Controllers;

use App\Models\CheckPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPointController extends Controller
{
  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // con paginate() se puede paginar la consulta
    $checkpoints  = CheckPoint::paginate(10);
    return view($this::$viewDir . '.checkpoint', compact('checkpoints'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view($this::$viewDir . '.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(CheckPoint::$rules, CheckPoint::$rulesMessages);
    try {
      DB::beginTransaction();
      $checkpoint = new CheckPoint();
      $checkpoint->fill($validated);
      $checkpoint->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('checkpoint')->with('error', 'Ha ocurrido un error al intentar crear el punto de control.');
    }
    return redirect()->route('checkpoint')->with('success', 'El punto de control se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(CheckPoint $checkpoint)
  {
    return view($this::$viewDir . '.checkpoint', compact('checkpoint'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CheckPoint $checkpoint)
  {
    return view($this::$viewDir . '.checkpoint', compact('checkpoint'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, CheckPoint $checkpoint)
  {
    $validated = $request->validate(CheckPoint::$rules, CheckPoint::$rulesMessages);
    try {
      DB::beginTransaction();
      $checkpoint->fill($validated);
      $checkpoint->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('checkpoint', $checkpoint)->with('error', 'Ha ocurrido un error al intentar actualizar el punto de control.');
    }
    return redirect()->route('checkpoint')->with('success', 'El punto de control se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CheckPoint $checkpoint)
  {
    try {
      DB::beginTransaction();
      $inspCount = \App\Models\Inspection::where('id_checkpoints', $checkpoint->id_checkpoints)->count();
      $dialogCount = \App\Models\DailyDialog::where('id_checkpoints', $checkpoint->id_checkpoints)->count();
      $pauseCount = \App\Models\ActivePause::where('id_checkpoints', $checkpoint->id_checkpoints)->count();
      $testCount = \App\Models\AlcoholTest::where('id_checkpoints', $checkpoint->id_checkpoints)->count();
      $gpsCount = \App\Models\GPSControl::where('id_checkpoints', $checkpoint->id_checkpoints)->count();
      $total = $inspCount + $dialogCount + $pauseCount + $testCount + $gpsCount;
      if ($total > 0) {
        return redirect()->route('checkpoint')->with('error', 'No se puede eliminar el punto de control porque tiene registros asociados (' . $total . ').');
      }
      $this::softDelete($checkpoint);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('checkpoint')->with('error', 'Ha ocurrido un error al intentar eliminar el punto de control.');
    }
    return redirect()->route('checkpoint')->with('success', 'El punto de control se ha eliminado correctamente.');
  }
}
