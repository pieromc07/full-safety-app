<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Repository\BusinessRepository;
use App\Repository\EnterpriseRepository;
use App\Repository\ProductTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductTypeController extends Controller
{

  protected ProductTypeRepository $productTypeRepository;

  /**
   * Constructor
   *
   * @param ProductTypeRepository $productTypeRepository
   * @param BusinessRepository $businessRepository
   * @param EnterpriseRepository $enterpriseRepository
   */
  public function __construct(ProductTypeRepository $productTypeRepository)
  {
    $this->productTypeRepository = $productTypeRepository; // Dependency Injection
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search ?? '';
    $products_types = $this->productTypeRepository->searchMultipleNotDeleted(['name', 'code'], $search,  self::TAKE);
    return view('products.products_types.index', compact('products_types'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $product_type = new ProductType();
    return view('products.products_types.create', compact('product_type'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate(ProductType::$rules, ProductType::$messages);
    try {
      DB::beginTransaction();
      $this->productTypeRepository->create($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products_types.create')->with('error', 'Error al crear el tipo de producto')->withInput();
    }
    return redirect()->route('products_types')->with('success', 'Tipo de producto creado con éxito');
  }

  /**
   * Display the specified resource.
   */
  public function show(ProductType $product_type)
  {
    return view('products.products_types.show', compact('product_type'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ProductType $product_type)
  {

    return view('products.products_types.edit', compact('product_type'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ProductType $product_type)
  {
    $request->validate(ProductType::$rules, ProductType::$messages);
    try {
      DB::beginTransaction();
      $request->request->remove('id_users_inserted');
      $this->productTypeRepository->update($product_type->id_product_types, $request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products_types.edit', $product_type)->with('error', 'Error al actualizar el tipo de producto ' . $e->getMessage())->withInput();
    }
    return redirect()->route('products_types')->with('success', 'Tipo de producto actualizada con éxito');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ProductType $product_type)
  {
    try {
      DB::beginTransaction();
      if ($product_type->products->count() > 0) {
        return redirect()->route('products_types')->with('error', 'No se puede eliminar el tipo de producto porque tiene productos asociados');
      }
      $this->productTypeRepository->cuid_delete($product_type->id_product_types);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products_types')->with('error', 'Error al eliminar el tipo de producto' . $e->getMessage());
    }
    return redirect()->route('products_types')->with('success', 'Tipo de producto eliminado correctamente');
  }
}
