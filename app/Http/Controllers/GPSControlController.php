<?php

namespace App\Http\Controllers;

use App\Models\CheckPoint;
use App\Models\Enterprise;
use App\Models\GPSControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GPSControlController extends Controller
{

  public function index(Request $request)
  {
    $controls = GPSControl::paginate(10);
    return view('control.index', compact('controls'));
  }

  /**
   * Display the specified resource.
   */
  public function show(GPSControl $control)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('control.show', compact('control', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Display the specified resource.
   */
  public function edit(GPSControl $control)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('control.edit', compact('control', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, GPSControl $control)
  {
    $request->merge(['id_users' => Auth::user()->id_users]);
    $request->validate(GPSControl::$rules, GPSControl::$messages);
    try {
      DB::beginTransaction();
      $control->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_checkpoints' => $request->id_checkpoints,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'option' => $request->option,
        'state' => $request->state,
        'observation' => $request->observation,
        'id_users' => $request->id_users,
      ]);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('controls', $control->id_gps_controls)->with('error', 'Ocurrió un error al actualizar el Control GPS' . $e->getMessage())->withInput();
    }
    return redirect()->back()->with('success', 'Control GPS actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(GPSControl $control)
  {
    try {
      DB::beginTransaction();
      self::dropImage($control->photo_one);
      self::dropImage($control->photo_two);
      $control->delete();
      DB::commit();
      return redirect()->route('controls')->with('success', 'Control GPS Eliminado');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('controls')->with('error', 'Ocurrió un error al eliminar el Control GPS' . $e->getMessage());
    }
  }
}
