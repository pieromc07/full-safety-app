<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductTypeController extends Controller
{
  static $viewDir = 'maintenance';

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $productTypes = ProductType::with('parent')->whereNull('cuid_deleted')->paginate(10);
    $parentTypes = ProductType::whereNull('parent_id')->whereNull('cuid_deleted')->get();
    return view($this::$viewDir . '.producttype', compact('productTypes', 'parentTypes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(ProductType::$rules, ProductType::$messages);
    try {
      DB::beginTransaction();
      $productType = new ProductType();
      $productType->fill($validated);
      if ($request->parent_id) {
        $productType->parent_id = $request->parent_id;
      }
      $productType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('producttype')->with('error', 'Ha ocurrido un error al intentar crear el tipo de producto.');
    }
    return redirect()->route('producttype')->with('success', 'El tipo de producto se ha creado correctamente.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ProductType $productType)
  {
    $validated = $request->validate(ProductType::$rules, ProductType::$messages);
    try {
      DB::beginTransaction();
      $productType->fill($validated);
      $productType->parent_id = $request->parent_id ?: null;
      $productType->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('producttype')->with('error', 'Ha ocurrido un error al intentar actualizar el tipo de producto.');
    }
    return redirect()->route('producttype')->with('success', 'El tipo de producto se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ProductType $productType)
  {
    try {
      DB::beginTransaction();
      if ($productType->products()->whereNull('cuid_deleted')->count() > 0) {
        return redirect()->route('producttype')->with('error', 'No se puede eliminar el tipo de producto porque tiene productos asociados.');
      }
      $children = ProductType::where('parent_id', $productType->id_product_types)->whereNull('cuid_deleted')->count();
      if ($children > 0) {
        return redirect()->route('producttype')->with('error', 'No se puede eliminar el tipo de producto porque tiene sub-tipos asociados.');
      }
      $this::softDelete($productType);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('producttype')->with('error', 'Ha ocurrido un error al intentar eliminar el tipo de producto.');
    }
    return redirect()->route('producttype')->with('success', 'El tipo de producto se ha eliminado correctamente.');
  }

  /**
   * Find records by parent_id (JSON API for cascading dropdowns).
   */
  public function findByParentId($parent_id)
  {
    $product_types = ProductType::where('parent_id', $parent_id)->get();
    return response()->json($product_types);
  }
}
