<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\Targeted;
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
    $enterpriseTransports = Enterprise::where('id_enterprise_types', 2)
      ->whereNull('cuid_deleted')
      ->get();

    // Roles disponibles: hijos del dirigido raíz "Persona"
    $personaRoot = Targeted::where('name', 'Persona')->whereNull('targeted_id')->first();
    $targetedRoles = $personaRoot
      ? Targeted::where('targeted_id', $personaRoot->id_targeteds)->whereNull('cuid_deleted')->get()
      : collect();

    // Id del rol "Otro" — el único que requiere job_title
    $otroRoleId = $targetedRoles->firstWhere('name', 'Otro')?->id_targeteds;

    $employees = Employee::with('enterpriseTransport', 'targeted')
      ->whereNull('cuid_deleted')
      ->paginate(10);

    return view($this::$viewDir . '.employees', compact('employees', 'enterpriseTransports', 'targetedRoles', 'otroRoleId'));
  }

  /**
   * Devuelve el id_targeteds del rol "Otro" (hijo de Persona). Null si no existe.
   */
  private function otroRoleId(): ?int
  {
    return Targeted::where('name', 'Otro')
      ->whereHas('targeted', fn ($q) => $q->where('name', 'Persona'))
      ->value('id_targeteds');
  }

  /**
   * Validación común a store/update. job_title obligatorio solo si rol = "Otro".
   */
  private function validateRequest(Request $request): array
  {
    $otroId = $this->otroRoleId();

    return $request->validate([
      'document' => 'required|string|max:16',
      'name' => 'required|string|max:50',
      'lastname' => 'required|string|max:50',
      'id_transport_enterprises' => 'required|exists:enterprises,id_enterprises',
      'id_targeteds' => 'nullable|exists:targeteds,id_targeteds',
      'job_title' => [
        'nullable',
        'string',
        'max:128',
        $otroId ? "required_if:id_targeteds,{$otroId}" : '',
      ],
    ], Employee::$rulesMessages + [
      'job_title.required_if' => 'El cargo es requerido cuando el rol es "Otro".',
    ]);
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
    $validated = $this->validateRequest($request);
    $otroId = $this->otroRoleId();

    try {
      DB::beginTransaction();
      $employee = new Employee();
      $employee->document = $validated['document'];
      $employee->name = $validated['name'];
      $employee->lastname = $validated['lastname'];
      $employee->fullname = $validated['name'] . ' ' . $validated['lastname'];
      $employee->id_transport_enterprises = $validated['id_transport_enterprises'];
      $employee->id_targeteds = $validated['id_targeteds'] ?? null;
      // job_title solo se persiste cuando el rol es "Otro".
      $employee->job_title = ($validated['id_targeteds'] ?? null) === $otroId
        ? ($validated['job_title'] ?? null)
        : null;
      $employee->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('employee')->with('error', 'Ha ocurrido un error al intentar crear el personal.');
    }
    return redirect()->route('employee')->with('success', 'El personal se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Employee $employee)
  {
    return view($this::$viewDir . '.show', compact('employee'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Employee $employee)
  {
    return view($this::$viewDir . '.edit', compact('employee'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Employee $employee)
  {
    $validated = $this->validateRequest($request);
    $otroId = $this->otroRoleId();

    try {
      DB::beginTransaction();
      $employee->document = $validated['document'];
      $employee->name = $validated['name'];
      $employee->lastname = $validated['lastname'];
      $employee->fullname = $validated['name'] . ' ' . $validated['lastname'];
      $employee->id_transport_enterprises = $validated['id_transport_enterprises'];
      $employee->id_targeteds = $validated['id_targeteds'] ?? null;
      $employee->job_title = ($validated['id_targeteds'] ?? null) === $otroId
        ? ($validated['job_title'] ?? null)
        : null;
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
  public function destroy(Employee $employee)
  {
    try {
      DB::beginTransaction();
      $testDetailCount = \App\Models\AlcoholTestDetail::where('id_employees', $employee->id_employees)->count();
      if ($testDetailCount > 0) {
        return redirect()->route('employee')->with('error', 'No se puede eliminar el personal porque tiene pruebas de alcohol asociadas.');
      }
      if ($employee->id_users) {
        return redirect()->route('employee')->with('error', 'No se puede eliminar el personal porque tiene un usuario asignado.');
      }
      $this::softDelete($employee);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('employee')->with('error', 'Ha ocurrido un error al intentar eliminar el personal.');
    }
    return redirect()->route('employee')->with('success', 'El personal se ha eliminado correctamente.');
  }
}
