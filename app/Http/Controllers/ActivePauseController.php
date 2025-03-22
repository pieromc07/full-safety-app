<?php

namespace App\Http\Controllers;

use App\Models\ActivePause;
use App\Models\CheckPoint;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActivePauseController extends Controller
{

  public function index(Request $request)
  {
    $actives = ActivePause::paginate(10);
    return view('active.index', compact('actives'));
  }

  /**
   * Display the specified resource.
   */
  public function show(ActivePause $active)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('active.show', compact('active', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ActivePause $active)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('active.edit', compact('active', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ActivePause $active)
  {
    $request->merge(['id_users' => Auth::user()->id_users]);
    $request->validate(ActivePause::$rules, ActivePause::$messages);
    try {
      DB::beginTransaction();
      $active->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_checkpoints' => $request->id_checkpoints,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'participants' => $request->participants,
      ]);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('actives.edit', $active->id_active_pauses)->with('error', 'Ocurrió un error al actualizar el pausa activa' . $e->getMessage())->withInput();
    }
    return redirect()->back()->with('success', 'Pausa activa actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ActivePause $active)
  {
    try {
      DB::beginTransaction();
      self::dropImage($active->photo_one);
      self::dropImage($active->photo_two);
      $active->delete();
      DB::commit();
      return redirect()->route('actives')->with('success', 'Pausa activa Eliminado');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('actives')->with('error', 'Ocurrió un error al eliminar el Pausa activa' . $e->getMessage());
    }
  }
}
