<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{



  /**
   * Display a listing of the resource.
   */
  public function index(Request $request) {}

  /**
   * Show the form for creating a new resource.
   */
  public function create() {}

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {}

  /**
   * Display the specified resource.
   */
  public function show(ProductType $product_type) {}

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ProductType $product_type) {}

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ProductType $product_type) {}

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ProductType $product_type) {}

  /**
   * find records by parent_id
   */
  public function findByParentId($parent_id) {
    $product_types = ProductType::where('parent_id', $parent_id)->get();
    return response()->json($product_types);
  }
}
