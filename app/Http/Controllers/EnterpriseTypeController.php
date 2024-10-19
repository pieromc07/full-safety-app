<?php

namespace App\Http\Controllers;

use App\Models\EnterpriseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterpriseTypeController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // con paginate() se puede paginar la consulta
    $enterpriseTypes  = EnterpriseType::paginate(10);
    return view($this::$viewDir . '.enterprisetype', compact('enterpriseTypes'));
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
    $validated = $request->validate(EnterpriseType::$rules, EnterpriseType::$rulesMessages);
    try {
      DB::beginTransaction();
      $enterpriseType = new EnterpriseType();
      $enterpriseType->fill($validated);
      $enterpriseType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprisetype')->with('error', 'Ha ocurrido un error al intentar crear el tipo de empresa.');
    }
    return redirect()->route('enterprisetype')->with('success', 'El tipo de empresa se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(EnterpriseType $enterpriseType)
  {
    return view($this::$viewDir . '.enterprisetype', compact('enterpriseType'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(EnterpriseType $enterpriseType)
  {
    return view($this::$viewDir . '.enterprisetype', compact('enterpriseType'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, EnterpriseType $enterpriseType)
  {
    $validated = $request->validate(EnterpriseType::$rules, EnterpriseType::$rulesMessages);
    try {
      DB::beginTransaction();
      $enterpriseType->fill($validated);
      $enterpriseType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprisetype', $enterpriseType)->with('error', 'Ha ocurrido un error al intentar actualizar el tipo de empresa.');
    }
    return redirect()->route('enterprisetype')->with('success', 'El tipo de empresa se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(EnterpriseType $enterpriseType)
  {
    try {
      DB::beginTransaction();
      $enterpriseType->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprisetype')->with('error', 'Ha ocurrido un error al intentar eliminar el tipo de empresa.');
    }
    return redirect()->route('enterprisetype')->with('success', 'El tipo de empresa se ha eliminado correctamente.');
  }
}
