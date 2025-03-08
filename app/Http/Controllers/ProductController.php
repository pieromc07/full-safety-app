<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductEnterprise;
use App\Models\ProductType;
use App\Models\Unit;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {



    $products = Product::paginate(self::MEDIUMTAKE);
    $unit_types = UnitType::all();
    $parents = ProductType::where('parent_id', null)->get();
    return view($this::$viewDir . '.product', compact('products', 'unit_types', 'parents'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create() {}

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(Product::$rules, Product::$messages);
    try {
      DB::beginTransaction();
      Product::create([
        'name' => $validated['name'],
        'number_onu' => $validated['number_onu'],
        'health' => $validated['health'],
        'flammability' => $validated['flammability'],
        'reactivity' => $validated['reactivity'],
        'special' => $validated['special'],
        'id_product_types' => $validated['id_product_types'],
        'id_unit_types' => $validated['id_unit_types'],
        'id_users_inserted' => auth()->id(),
        'id_users_updated' => auth()->id(),
      ]);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products')->with('error', 'Ha ocurrido un error al intentar crear el producto. ' . $e->getMessage())->withInput();
    }
    return redirect()->route('products')->with('success', 'El producto se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    $product->load('productType', 'unitType');
    $productEnterprise = ProductEnterprise::where('id_products', $product->id_products)->first();
    $products = ProductEnterprise::where('id_supplier_enterprises', $productEnterprise->id_supplier_enterprises)
      ->where('id_transport_enterprises', $productEnterprise->id_transport_enterprises)->where('id_products', '!=', $product->id_products)
      ->with('product')
      ->get();

    $units = Unit::all();

    return response()->json([
      'product' => $product,
      'productEnterprise' => $productEnterprise,
      'products' => $products,
      'units' => $units
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product) {}

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Product $product)
  {
    $validated = $request->validate(Product::$rules, Product::$messages);
    try {
      DB::beginTransaction();
      $product->fill($validated);
      $product->id_users_updated = auth()->id();
      $product->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products')->with('error', 'Ha ocurrido un error al intentar actualizar el producto. ' . $e->getMessage())->withInput();
    }
    return redirect()->route('products')->with('success', 'El producto se ha actualizado correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    try {
      DB::beginTransaction();
      $product->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products')->with('error', 'Ha ocurrido un error al intentar eliminar el producto. ' . $e->getMessage());
    }
    return redirect()->route('products')->with('success', 'El producto se ha eliminado correctamente.');
  }
}
