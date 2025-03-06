<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $enterpriseTransports = Enterprise::where('id_enterprise_types', '=', 2)->get();
    $employees = Employee::paginate(5);
    return view($this::$viewDir . '.employees', compact('employees', 'enterpriseTransports'));
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
    $validated = $request->validate(Employee::$rules, Employee::$rulesMessages);
    try {
      DB::beginTransaction();
      $employee = new employee();
      $employee->document = $validated['document'];
      $employee->name = $validated['name'];
      $employee->lastname = $validated['lastname'];
      $employee->fullname = $validated['name'] . ' ' . $validated['lastname'];
      $employee->id_transport_enterprises = $validated['id_transport_enterprises'];
      $employee->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('employee')->with('error', 'Ha ocurrido un error al intentar crear el personal. ');
    }
    return redirect()->route('employee')->with('success', 'El personal se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(employee $employee)
  {

    return view($this::$viewDir . '.show', compact('employee'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(employee $employee)
  {
    return view($this::$viewDir . '.edit', compact('employee'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, employee $employee)
  {
    $validated = $request->validate(Employee::$rules, Employee::$rulesMessages);
    try {
      DB::beginTransaction();
      $employee->document = $validated['document'];
      $employee->name = $validated['name'];
      $employee->lastname = $validated['lastname'];
      $employee->fullname = $validated['name'] . ' ' . $validated['lastname'];
      $employee->id_transport_enterprises = $validated['id_transport_enterprises'];
      $employee->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('employee')->with('error', 'Ha ocurrido un error al intentar actualizar el personal.');
    }
    return redirect()->route('employee')->with('success', 'El personal se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(employee $employee)
  {
    try {
      DB::beginTransaction();
      $employee->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('employee')->with('error', 'Ha ocurrido un error al intentar eliminar el personal.');
    }
    return redirect()->route('employee')->with('success', 'El personal se ha eliminado correctamente.');
  }
}
