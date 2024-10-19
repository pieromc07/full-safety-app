<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\EnterpriseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterpriseController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $enterpriseType = $request->get('enterprise_type_id');
    if ($enterpriseType) {
      $enterprises = Enterprise::where('enterprise_type_id', $enterpriseType)->paginate(10);
    } else {
      $enterprises = Enterprise::paginate(10);
    }
    $enterpriseTypes = EnterpriseType::all();
    $onlyTransportEnterprises = Enterprise::onlySupplierEnterprises();
    return view($this::$viewDir . '.enterprise', compact('enterprises', 'enterpriseTypes', 'onlyTransportEnterprises'));
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
    $validated = $request->validate(Enterprise::$rules, Enterprise::$rulesMessages);
    try {
      DB::beginTransaction();
      $enterprise = new Enterprise();
      $enterprise->name = $validated['name'];
      $enterprise->ruc = $validated['ruc'];
      if ($request->hasFile('image')) {
        $enterprise->image = $this::saveImage($request->file('image'), 'enterprises');
      }
      $enterprise->enterprise_type_id = $validated['enterprise_type_id'];
      $enterprise->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprise')->with('error', 'Ha ocurrido un error al intentar crear la empresa.');
    }
    return redirect()->route('enterprise')->with('success', 'La empresa se ha creado correctamente.');
  }

  public function assign(Request $request)
  {
    $validated = $request->validate([
      'supplier_enterprise_id' => 'required|exists:enterprises,id',
      'transport_enterprise_id' => 'required|exists:enterprises,id'
    ]);
    try {
      DB::beginTransaction();
      $relsExists = EnterpriseRelsEnterprise::uniqueSupplierAndTransport($validated['supplier_enterprise_id'], $validated['transport_enterprise_id']);
      if ($relsExists) {
        return redirect()->route('enterprise')->with('error', 'Las empresas ya están asignadas.');
      }
      $enterpriseRelsEnterprise = new EnterpriseRelsEnterprise();
      $enterpriseRelsEnterprise->supplier_enterprise_id = $validated['supplier_enterprise_id'];
      $enterpriseRelsEnterprise->transport_enterprise_id = $validated['transport_enterprise_id'];
      $enterpriseRelsEnterprise->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprise')->with('error', 'Ha ocurrido un error al intentar asignar las empresas.');
    }
    return redirect()->route('enterprise')->with('success', 'Las empresas se han asignado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Enterprise $enterprise)
  {
    return response()->json($enterprise->transportEnterprises());
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Enterprise $enterprise)
  {
    return view($this::$viewDir . '.edit', compact('enterprise'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Enterprise $enterprise)
  {
    $validated = $request->validate(Enterprise::$rules, Enterprise::$rulesMessages);
    try {
      DB::beginTransaction();
      $enterprise->name = $validated['name'];
      $enterprise->ruc = $validated['ruc'];
      if ($request->hasFile('image')) {
        if ($this::dropImage($enterprise->image)) {
          $enterprise->image = $this::saveImage($request->file('image'), 'enterprises');
        }
      }
      $enterprise->enterprise_type_id = $validated['enterprise_type_id'];
      $enterprise->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprise')->with('error', 'Ha ocurrido un error al intentar actualizar la empresa.');
    }
    return redirect()->route('enterprise')->with('success', 'La empresa se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Enterprise $enterprise)
  {
    try {
      DB::beginTransaction();
      $enterprise->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('enterprise')->with('error', 'Ha ocurrido un error al intentar eliminar la empresa.');
    }
    return redirect()->route('enterprise')->with('success', 'La empresa se ha eliminado correctamente.');
  }
}
