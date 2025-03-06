<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Repository\BranchRepository;
use App\Repository\DepartamentRepository;
use App\Repository\DistrictRepository;
use App\Repository\EnterpriseRepository;
use App\Repository\ProvinceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{

  protected BranchRepository $brandRepository;
  protected EnterpriseRepository $enterpriseRepository;
  protected DistrictRepository $districtRepository;
  protected ProvinceRepository $provinceRepository;
  protected DepartamentRepository $departamentRepository;
  /**
   * Constructor
   */
  public function __construct(BranchRepository $brandRepository, EnterpriseRepository $enterpriseRepository, DistrictRepository $districtRepository, ProvinceRepository $provinceRepository, DepartamentRepository $departamentRepository)
  {
    $this->brandRepository = $brandRepository;
    $this->enterpriseRepository = $enterpriseRepository;
    $this->districtRepository = $districtRepository;
    $this->provinceRepository = $provinceRepository;
    $this->departamentRepository = $departamentRepository;
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

    $search = $request->search ?? '';
    $branches = $this->brandRepository->searchMultipleByEnterprise(['name'], $search, self::TAKE, $request->session()->get('enterpriseId'));
    return view('company.branches.index', compact('branches'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $this->brandRepository->setEnterpriseId(request());
    $this->brandRepository->setBranchId(request());
    $departaments = $this->departamentRepository->all();
    $provinces = $this->provinceRepository->all();
    $districts = $this->districtRepository->all();
    $enterprises = $this->enterpriseRepository->all();
    $branch = new Branch();
    return view('company.branches.create', compact('branch', 'departaments', 'provinces', 'districts', 'enterprises'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $this->brandRepository->setEnterpriseId($request);
    $this->brandRepository->setBranchId($request);
    if (!isset($request->id_enterprises)) {
      $request->merge(['id_enterprises' => $request->session()->get('enterpriseId')]);
    }
    $request->validate(Branch::$rules, Branch::$messages);
    try {
      DB::beginTransaction();
      $request->merge(['is_main' => 0]);
      $request->merge(['is_active' => 1]);
      if (User::find(auth()->id())->hasRole('master')) {
        $branch = $this->brandRepository->create($request->all());
      } else {
        $branch = $this->brandRepository->create($request->all());
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('branches.create')->with('error', 'Error al registrar la sucursal' . $e->getMessage())->withInput();
    }
    return redirect()->route('branches')->with('success', 'Sucursal registrada correctamente');
  }

  /**
   * Display the specified resource.
   */
  public function show(Branch $branch)
  {
    $this->brandRepository->setEnterpriseId(request());
    $this->brandRepository->setBranchId(request());
    $departaments = $this->departamentRepository->all();
    $provinces = $this->provinceRepository->all();
    $districts = $this->districtRepository->all();
    $enterprises = $this->enterpriseRepository->all();
    return view('company.branches.show', compact('branch', 'departaments', 'provinces', 'districts', 'enterprises'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Branch $branch)
  {
    $this->brandRepository->setEnterpriseId(request());
    $this->brandRepository->setBranchId(request());
    $departaments = $this->departamentRepository->all();
    $provinces = $this->provinceRepository->all();
    $districts = $this->districtRepository->all();
    $enterprises = $this->enterpriseRepository->all();
    return view('company.branches.edit', compact('branch', 'departaments', 'provinces', 'districts', 'enterprises'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Branch $branch)
  {
    $this->brandRepository->setEnterpriseId($request);
    $this->brandRepository->setBranchId($request);
    if (!isset($request->id_enterprises)) {
      $request->merge(['id_enterprises' => $request->session()->get('enterpriseId')]);
    }
    $request->validate(Branch::$rules, Branch::$messages);
    try {
      DB::beginTransaction();
      $branch->update($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('branches.edit', $branch)->with('error', 'Error al actualizar la sucursal' . $e->getMessage())->withInput();
    }
    return redirect()->route('branches')->with('success', 'Sucursal actualizada correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Branch $branch)
  {
    $this->brandRepository->setEnterpriseId(request());
    $this->brandRepository->setBranchId(request());
    try {
      DB::beginTransaction();
      $this->brandRepository->delete($branch->id);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('branches')->with('error', 'Error al eliminar la sucursal' . $e->getMessage());
    }
    return redirect()->route('branches')->with('success', 'Sucursal eliminada correctamente');
  }
}
