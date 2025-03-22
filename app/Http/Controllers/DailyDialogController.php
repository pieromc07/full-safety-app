<?php

namespace App\Http\Controllers;

use App\Models\CheckPoint;
use App\Models\DailyDialog;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DailyDialogController extends Controller
{

  public function index(Request $request)
  {
    $dialogues = DailyDialog::paginate(10);
    return view('dialogue.index', compact('dialogues'));
  }

  /**
   * Display the specified resource.
   */
  public function show(DailyDialog $dialogue)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('dialogue.show', compact('dialogue', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(DailyDialog $dialogue)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    return view('dialogue.edit', compact('dialogue', 'checkpoints', 'transports', 'suppliers'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, DailyDialog $dialogue)
  {
    $request->merge(['id_users' => Auth::user()->id_users]);
    $request->validate(DailyDialog::$rules, DailyDialog::$messages);
    try {
      DB::beginTransaction();
      $dialogue->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_checkpoints' => $request->id_checkpoints,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'topic' => $request->topic,
        'participants' => $request->participants,
      ]);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('dialogues.edit', $dialogue->id_daily_dialogs)->with('error', 'Ocurrió un error al actualizar el dialogo diario' . $e->getMessage())->withInput();
    }
    return redirect()->back()->with('success', 'Dialogo diario actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(DailyDialog $dialogue)
  {
    try {
      DB::beginTransaction();
      self::dropImage($dialogue->photo_one);
      self::dropImage($dialogue->photo_two);
      $dialogue->delete();
      DB::commit();
      return redirect()->route('dialogues')->with('success', 'Dialogo diario Eliminado');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('dialogues')->with('error', 'Ocurrió un error al eliminar el Dialogo diario' . $e->getMessage());
    }
  }
}
