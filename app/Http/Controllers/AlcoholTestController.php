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
use Illuminate\Support\Facades\Log;

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
    // Validar campos principales
    $request->validate(AlcoholTest::$rules, AlcoholTest::$messages);

    // Validar details si vienen
    if ($request->has('details')) {
      $detailsRules = [
        'details' => 'array',
        'details.*.result' => 'nullable|numeric',
        'details.*.state' => 'nullable|in:0,1',
        'details.*.id_alcohol_test_details' => 'nullable|exists:alcohol_test_details,id_alcohol_test_details',
        'details.*.employee_id' => 'nullable|exists:employees,id_employees',
      ];
      $request->validate($detailsRules);
    }
    try {
      DB::beginTransaction();
      $test->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_checkpoints' => $request->id_checkpoints,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'id_users' => $request->id_users,
      ]);

      // Procesar details si vienen (ya validados)
      if ($request->has('details')) {
        $details = $request->input('details');
        foreach ($details as $d) {
          if (is_string($d)) {
            $d = json_decode($d, true);
          }
          if (!is_array($d)) continue;

          // eliminar si viene flag delete
          if (!empty($d['id_alcohol_test_details']) && !empty($d['delete'])) {
            $detailToDelete = \App\Models\AlcoholTestDetail::find($d['id_alcohol_test_details']);
            if ($detailToDelete) {
              $detailToDelete->delete();
            }
            continue;
          }

          // si viene id_alcohol_test_details -> actualizar
          if (!empty($d['id_alcohol_test_details'])) {
            $detailModel = \App\Models\AlcoholTestDetail::find($d['id_alcohol_test_details']);
            if ($detailModel) {
              $detailModel->result = $d['result'] ?? $detailModel->result;
              $detailModel->state = $d['state'] ?? $detailModel->state;
              $detailModel->save();
            }
            continue;
          }

          // crear nuevo detalle si viene employee_id
          if (!empty($d['employee_id'])) {
            \App\Models\AlcoholTestDetail::create([
              'id_alcohol_tests' => $test->id_alcohol_tests,
              'id_employees' => $d['employee_id'],
              'result' => $d['result'] ?? null,
              'state' => $d['state'] ?? null,
              'photo_one_uri' => $d['photo_one'] ?? null,
              'photo_two_uri' => $d['photo_two'] ?? null,
            ]);
          }
        }

        // Validar que al menos quede un detalle asociado
        $remaining = \App\Models\AlcoholTestDetail::where('id_alcohol_tests', $test->id_alcohol_tests)->count();
        if ($remaining == 0) {
          throw new \Exception('La Prueba de Alcohol debe tener al menos un detalle.');
        }
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error updating AlcoholTest: ' . $e->getMessage());
      return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la Prueba de Alcohol');
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

      // Eliminar imágenes del test si existen
      if (!empty($test->photo_one)) {
        self::dropImage($test->photo_one);
      }
      if (!empty($test->photo_two)) {
        self::dropImage($test->photo_two);
      }

      // Eliminar imágenes y registros de detalles asociados
      foreach ($test->details as $detail) {
        try {
          if (!empty($detail->photo_one_uri)) {
            self::dropImage($detail->photo_one_uri);
          }
          if (!empty($detail->photo_two_uri)) {
            self::dropImage($detail->photo_two_uri);
          }
        } catch (\Exception $e) {
          // No detener la eliminación si no encuentra la imagen; loguear
          Log::warning('Error deleting detail image: ' . $e->getMessage());
        }
        $detail->delete();
      }

      // Finalmente eliminar la prueba
      $test->delete();

      DB::commit();
      return redirect()->route('tests')->with('success', 'Prueba de Alcohol Eliminado');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error deleting AlcoholTest: ' . $e->getMessage());
      return redirect()->route('tests')->with('error', 'Ocurrió un error al eliminar la Prueba de Alcohol');
    }
  }
}
