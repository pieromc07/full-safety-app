<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
  static $viewDir = 'maintenance';

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $companies = Company::paginate(10);
    return view($this::$viewDir . '.company', compact('companies'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(Company::$rules, Company::$rulesMessages);
    try {
      DB::beginTransaction();
      $company = new Company();
      $company->fill($validated);
      if ($request->hasFile('logo')) {
        $company->logo = $this::saveImage($request->file('logo'), 'companies');
      }
      $company->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('company')->with('error', 'Ha ocurrido un error al intentar crear la empresa: ' . $e->getMessage());
    }
    return redirect()->route('company')->with('success', 'La empresa se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Company $company)
  {
    $validated = $request->validate(Company::$rules, Company::$rulesMessages);
    try {
      DB::beginTransaction();
      $company->fill($validated);
      if ($request->hasFile('logo')) {
        if ($company->logo) {
          $this::dropImage($company->logo);
        }
        $company->logo = $this::saveImage($request->file('logo'), 'companies');
      }
      $company->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('company')->with('error', 'Ha ocurrido un error al intentar actualizar la empresa: ' . $e->getMessage());
    }
    return redirect()->route('company')->with('success', 'La empresa se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Company $company)
  {
    try {
      DB::beginTransaction();
      if ($company->logo) {
        $this::dropImage($company->logo);
      }
      $this::softDelete($company);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('company')->with('error', 'Ha ocurrido un error al intentar eliminar la empresa: ' . $e->getMessage());
    }
    return redirect()->route('company')->with('success', 'La empresa se ha eliminado correctamente.');
  }
}
