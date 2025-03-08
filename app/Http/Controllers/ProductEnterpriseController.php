<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Product;
use App\Models\ProductEnterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductEnterpriseController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $productEnterprises = ProductEnterprise::paginate(self::MEDIUMTAKE);
    $supplierEnterprises = Enterprise::where('id_enterprise_types', 1)->get();
    $products = Product::all();
    return view('maintenance.productenterprise', compact('productEnterprises', 'supplierEnterprises', 'products'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate(ProductEnterprise::$rules, ProductEnterprise::$messages);
    try {
      DB::beginTransaction();
      ProductEnterprise::create($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('productenterprises')->with('error', 'Ha ocurrido un error al intentar crear la empresa proveedora.');
    }
    return redirect()->route('productenterprises')->with('success', 'La empresa proveedora se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(ProductEnterprise $productEnterprise)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ProductEnterprise $productEnterprise)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ProductEnterprise $productEnterprise)
  {
    $request->validate(ProductEnterprise::$rules, ProductEnterprise::$messages);
    try {
      DB::beginTransaction();
      $productEnterprise->update($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('productenterprises')->with('error', 'Ha ocurrido un error al intentar actualizar la empresa proveedora.');
    }
    return redirect()->route('productenterprises')->with('success', 'La empresa proveedora se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ProductEnterprise $productEnterprise)
  {
    try {
      DB::beginTransaction();
      $productEnterprise->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('productenterprises')->with('error', 'Ha ocurrido un error al intentar eliminar la empresa proveedora.');
    }
    return redirect()->route('productenterprises')->with('success', 'La empresa proveedora se ha eliminado correctamente.');
  }

  /**
   * Get Products by Supplier Enterprise and Transport Enterprise
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getProductsBySupplierAndTransportEnterprise($id_supplier_enterprises, $id_transport_enterprises)
  {

    try {

      if (!$id_supplier_enterprises || !$id_transport_enterprises) {
        return response()->json(['error' => 'Los parámetros no son válidos.'], 500);
      }
      $products = ProductEnterprise::where('id_supplier_enterprises', $id_supplier_enterprises)
        ->where('id_transport_enterprises', $id_transport_enterprises)
        ->with('product')
        ->get();
      return response()->json($products);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()]);
    }
  }
}
