<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Repository\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{

  protected UnitRepository $unitRepository;

  /**
   * Constructor
   *
   * @param UnitRepository $unitRepository
   * @param EnterpriseRepository $enterpriseRepository
   */
  public function __construct(UnitRepository $unitRepository)
  {
    $this->unitRepository = $unitRepository; // Dependency Injection
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search ?? '';
    $units = $this->unitRepository->searchNotDeleted('name', $search, self::TAKE);
    return view('products.units.index', compact('units'));
  }


  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $unit = new Unit();
    return view('products.units.create', compact('unit'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {

    $request->validate(Unit::$rules, Unit::$messages);
    try {
      DB::beginTransaction();
      $this->unitRepository->create($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('units.create')->with('error', 'Error al crear la unidad ' . $e->getMessage())->withInput();
    }
    return redirect()->route('units')->with('success', 'Unidad creada con éxito');
  }

  /**
   * Display the specified resource.
   */
  public function show(Unit $unit)
  {
    return view('products.units.show', compact('unit'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Unit $unit)
  {
    return view('products.units.edit', compact('unit'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Unit $unit)
  {
    $request->validate(Unit::$rules, Unit::$messages);
    try {
      DB::beginTransaction();
      $request->request->remove('id_users_inserted');
      $this->unitRepository->update($unit->id_units, $request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('units.edit', $unit)->with('error', 'Error al actualizar la unidad ' . $e->getMessage())->withInput();
    }
    return redirect()->route('units')->with('success', 'Unidad actualizada con éxito');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Unit $unit)
  {
    try {
      DB::beginTransaction();
      if ($unit->products->count() > 0) {
        return redirect()->route('units')->with('error', 'No se puede eliminar la unidad porque tiene productos asociados');
      }
      $this->unitRepository->cuid_delete($unit->id_units);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('units')->with('error', 'Error al eliminar la unidad' . $e->getMessage());
    }
    return redirect()->route('units')->with('success', 'Unidad eliminada correctamente');
  }
}
