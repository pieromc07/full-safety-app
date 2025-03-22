<?php

namespace App\Http\Controllers;

use App\Models\AlcoholTest;
use App\Models\CheckPoint;
use App\Models\Employee;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlcoholTestController extends Controller
{

  public function index(Request $request)
  {
    $tests = AlcoholTest::paginate(10);
    return view('test.index', compact('tests'));
  }

  /**
   * Display the specified resource.
   */
  public function show(AlcoholTest $test)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    $employees = Employee::all();
    return view('test.show', compact('test', 'checkpoints', 'transports', 'suppliers', 'employees'));
  }

  /**
   * Display the specified resource.
   */
  public function edit(AlcoholTest $test)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    $employees = Employee::all();
    return view('test.edit', compact('test', 'checkpoints', 'transports', 'suppliers', 'employees'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, AlcoholTest $test)
  {
    $request->merge(['id_users' => Auth::user()->id_users]);
    $request->validate(AlcoholTest::$rules, AlcoholTest::$messages);
    try {
      DB::beginTransaction();
      $test->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_checkpoints' => $request->id_checkpoints,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'id_employees' => $request->id_employees,
        'result' => $request->result,
        'state' => $request->result > 0 ? 1 : 0,
        'id_users' => $request->id_users,
      ]);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('tests', $test->id_alcohol_tests)->with('error', 'Ocurrió un error al actualizar el Prueba de Alcohol' . $e->getMessage())->withInput();
    }
    return redirect()->back()->with('success', 'Prueba de Alcohol actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(AlcoholTest $test)
  {
    try {
      DB::beginTransaction();
      self::dropImage($test->photo_one);
      self::dropImage($test->photo_two);
      $test->delete();
      DB::commit();
      return redirect()->route('tests')->with('success', 'Prueba de Alcohol Eliminado');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('tests')->with('error', 'Ocurrió un error al eliminar el Prueba de Alcohol' . $e->getMessage());
    }
  }
}
