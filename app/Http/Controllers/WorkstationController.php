<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workstation;
use App\Repository\EnterpriseRepository;
use App\Repository\WorkstationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkstationController extends Controller
{

  protected WorkstationRepository $repository;
  protected EnterpriseRepository $enterpriseRepository;

  /**
   * Constructor
   */
  public function __construct(WorkstationRepository $repository, EnterpriseRepository $enterpriseRepository)
  {
    $this->repository = $repository; // Dependency Injection
    $this->enterpriseRepository = $enterpriseRepository; // Dependency Injection
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search ?? '';
    $workstations = $this->repository->searchByEnterprise('name', $search, self::TAKE);
    return view('workstations.index', compact('workstations'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $enterprises = $this->enterpriseRepository->all();
    $workstation = new Workstation();
    return view('workstations.create', compact('workstation', 'enterprises'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate(Workstation::$rules, Workstation::$messages);
    try {
      DB::beginTransaction();
      $this->repository->create($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('workstations.create')->with('error', 'Error al crear la estación de trabajo' . $e->getMessage())->withInput();
    }
    return redirect()->route('workstations')->with('success', 'Estación de trabajo creada');
  }

  /**
   * Display the specified resource.
   */
  public function show(Workstation $workstation)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Workstation $workstation)
  {
    $enterprises = $this->enterpriseRepository->all();
    return view('workstations.edit', compact('workstation', 'enterprises'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Workstation $workstation)
  {

    $request->validate(Workstation::$rules, Workstation::$messages);
    try {
      DB::beginTransaction();
      $this->repository->update($workstation->id_workstations, $request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('workstations.edit')->with('error', 'Error al actualizar la estación de trabajo' . $e->getMessage())->withInput();
    }
    return redirect()->route('workstations')->with('success', 'Estación de trabajo actualizada');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Workstation $workstation)
  {
    try {
      DB::beginTransaction();
      if ($workstation->employees->count() > 0) {
        return redirect()->route('workstations')->with('error', 'Error al eliminar la estación de trabajo, tiene empleados asignados');
      }
      $this->repository->delete($workstation->id_workstations);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('workstations')->with('error', 'Error al eliminar la estación de trabajo' . $e->getMessage());
    }
    return redirect()->route('workstations')->with('success', 'Estación de trabajo eliminada');
  }
}
