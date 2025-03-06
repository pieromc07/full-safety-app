<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Repository\BranchRepository;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\EnterpriseRepository;
use App\Repository\LotRepository;
use App\Repository\ModeloRepository;
use App\Repository\ProductBranchRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use App\Repository\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  protected ProductRepository $productRepository;
  protected ProductBranchRepository $productBranchRepository;
  protected CategoryRepository $categoryRepository;
  protected ProductTypeRepository $productTypeRepository;
  protected ModeloRepository $modelRepository;
  protected BrandRepository $brandRepository;
  protected UnitRepository $unitRepository;
  protected BranchRepository $branchRepository;
  protected EnterpriseRepository $enterpriseRepository;
  protected LotRepository $lotRepository;

  /**
   * Constructor
   *
   * @param ProductRepository $productRepository
   * @param CategoryRepository $categoryRepository
   * @param ProductTypeRepository $productTypeRepository
   * @param ModeloRepository $modelRepository
   * @param BrandRepository $brandRepository
   * @param UnitRepository $unitRepository
   * @param BranchRepository $branchRepository
   * @param EnterpriseRepository $enterpriseRepository
   * @param ProductBranchRepository $productBranchRepository
   * @param LotRepository $lotRepository
   */
  public function __construct(
    ProductRepository $productRepository,
    ProductBranchRepository $productBranchRepository,
    CategoryRepository $categoryRepository,
    ProductTypeRepository $productTypeRepository,
    ModeloRepository $modelRepository,
    BrandRepository $brandRepository,
    UnitRepository $unitRepository,
    BranchRepository $branchRepository,
    EnterpriseRepository $enterpriseRepository,
    LotRepository $lotRepository
  ) {
    $this->productRepository = $productRepository;
    $this->productBranchRepository = $productBranchRepository;
    $this->categoryRepository = $categoryRepository;
    $this->productTypeRepository = $productTypeRepository;
    $this->modelRepository = $modelRepository;
    $this->brandRepository = $brandRepository;
    $this->unitRepository = $unitRepository;
    $this->branchRepository = $branchRepository;
    $this->enterpriseRepository = $enterpriseRepository;
    $this->lotRepository = $lotRepository;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search ?? '';
    $products = $this->productRepository->allSearchMultipleNotDeletedByEnterprise(['products.name', 'products.barcode'], $search, self::BIGTAKE, $request->session()->get('enterpriseId'));

    return view('products.index', compact('products', 'search'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = $this->categoryRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $models = $this->modelRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $brands = $this->brandRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $units = $this->unitRepository->all(request()->session()->get('enterpriseId'));
    $productTypes = $this->productTypeRepository->all();
    $branches = $this->branchRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $productBranches = [];
    $product = new Product();
    return view('products.create', compact('product', 'categories', 'models', 'brands', 'branches', 'units', 'productTypes', 'productBranches'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $this->replaceOrVerifyEnterprise();
    $request->request->add(['id_product_types' => $this->categoryRepository->getProductTypeIdByCategoryId($request->id_categories)]);
    // dd($request->all());
    if ($request->branches == null) {
      Product::$rules['branches'] = 'required';
      Product::$messages['branches.required'] = 'Debe seleccionar al menos una almacen';
    }
    $request->validate(Product::$rules, Product::$messages);
    try {
      $productId = $this->productRepository->createReturnId([
        'name' => $request->name,
        'description' => $request->description,
        'barcode' => $request->barcode ?? 'SIN CODIGO',
        'profit_margin' => $request->profit_margin,
        'cost' => $request->cost,
        'price' => $request->price,
        'priceb' => $request->priceb,
        'pricec' => $request->pricec,
        'priced' => $request->priced,
        'id_categories' => $request->id_categories,
        'id_models' => $request->id_models,
        'id_brands' => $request->id_brands,
        'id_units' => $request->id_units,
        'id_product_types' => $request->id_product_types,
        'id_enterprises' => $request->id_enterprises,
        'id_users_inserted' => $request->id_users_inserted,
        'id_users_updated' => $request->id_users_updated,
      ]);

      $branches = $request->branches;
      if ($branches != null) {
        foreach ($branches as $branch) {
          $this->productBranchRepository->createIfNotExists([
            'id_products' => $productId,
            'id_branches' => $branch,
            'stock' => 0,
            'price' => $request->price,
            'cost' => $request->cost,
            'id_enterprises' => $request->id_enterprises,
            'id_users_inserted' => $request->id_users_inserted,
            'id_users_updated' => $request->id_users_updated,
          ], $branch, $productId);
        }
      }
      if ($request->hasFile('image')) {
        $name = $this->saveFiles($request->file('image'), 'images/products', $this->productRepository->getUidById($productId));
        $this->productRepository->update($productId, ['image' => $name]);
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products.create')->with('error', 'Ocurrió un error al guardar el producto' . $e->getMessage())->withInput();
    }
    return redirect()->route('products')->with('success', 'Producto guardado correctamente');
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    $categories = $this->categoryRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $models = $this->modelRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $brands = $this->brandRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $units = $this->unitRepository->all(request()->session()->get('enterpriseId'));
    $productTypes = $this->productTypeRepository->all();
    $branches = $this->branchRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $productBranches = $this->productBranchRepository->allByProductAndEnterpriseWithBranchesChecked($product->id_products, request()->session()->get('enterpriseId'));
    foreach ($branches as $branch) {
      if ($productBranches->isEmpty()) {
        $branch->checked = false;
        $branch->stock = 0;
        $branch->price = 0;
        $branch->cost = 0;
      } else {
        foreach ($productBranches as $productBranch) {
          if ($branch->id_branches == $productBranch->id_branches) {
            $branch->checked = true;
            $branch->stock = $productBranch->stock;
            $branch->price = $productBranch->price;
            $branch->cost = $productBranch->cost;
          } else {
            $branch->checked = false;
            $branch->stock = 0;
            $branch->price = 0;
            $branch->cost = 0;
          }
        }
      }
    }
    return view('products.show', compact('product', 'categories', 'models', 'brands', 'branches', 'units', 'productTypes', 'productBranches'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    $categories = $this->categoryRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $models = $this->modelRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $brands = $this->brandRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $units = $this->unitRepository->all(request()->session()->get('enterpriseId'));
    $productTypes = $this->productTypeRepository->all();
    $branches = $this->branchRepository->allByEnterprise(request()->session()->get('enterpriseId'));
    $productBranches = $this->productBranchRepository->allByProductAndEnterpriseWithBranchesChecked($product->id_products, request()->session()->get('enterpriseId'));
    foreach ($branches as $branch) {
      if ($productBranches->isEmpty()) {
        $branch->checked = false;
        $branch->stock = 0;
        $branch->price = 0;
        $branch->cost = 0;
      } else {
        foreach ($productBranches as $productBranch) {
          if ($branch->id_branches == $productBranch->id_branches) {
            $branch->checked = true;
            $branch->stock = $productBranch->stock;
            $branch->price = $productBranch->price;
            $branch->cost = $productBranch->cost;
          } else {
            $branch->checked = false;
            $branch->stock = 0;
            $branch->price = 0;
            $branch->cost = 0;
          }
        }
      }
    }
    return view('products.edit', compact('product', 'categories', 'models', 'brands', 'branches', 'units', 'productTypes', 'productBranches'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Product $product)
  {
    $this->replaceOrVerifyEnterprise();
    $request->request->add(['id_product_types' => $this->categoryRepository->getProductTypeIdByCategoryId($request->id_categories)]);
    $request->validate(Product::$rules, Product::$messages);
    try {
      $this->productRepository->update($product->id_products, [
        'name' => $request->name,
        'description' => $request->description,
        'barcode' => $request->barcode ?? 'SIN CODIGO',
        'profit_margin' => $request->profit_margin,
        'cost' => $request->cost,
        'price' => $request->price,
        'priceb' => $request->priceb,
        'pricec' => $request->pricec,
        'priced' => $request->priced,
        'id_categories' => $request->id_categories,
        'id_models' => $request->id_models,
        'id_brands' => $request->id_brands,
        'id_units' => $request->id_units,
        'id_product_types' => $request->id_product_types,
        'id_enterprises' => $request->id_enterprises,
        'id_users_updated' => $request->id_users_updated,
      ]);

      $branches = $request->branches;
      if ($branches != null) {
        foreach ($branches as $branch) {
          $this->productBranchRepository->createIfNotExists([
            'id_products' => $product->id_products,
            'id_branches' => $branch,
            'stock' => 0,
            'price' => $request->price,
            'cost' => $request->cost,
            'id_enterprises' => $request->id_enterprises,
            'id_users_inserted' => $request->id_users_inserted,
            'id_users_updated' => $request->id_users_updated,
          ], $branch, $product->id_products);
        }
      } else {
        $this->productBranchRepository->deleteByProduct($product->id_products);
      }
      if ($request->hasFile('image')) {
        $this->deleteFiles($product->image);
        $name = $this->saveFiles($request->file('image'), 'images/products', $this->productRepository->getUidById($product->id_products));
        $this->productRepository->update($product->id_products, ['image' => $name]);
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products.edit', $product->id_products)->with('error', 'Ocurrió un error al actualizar el producto' . $e->getMessage())->withInput();
    }
    return redirect()->route('products')->with('success', 'Producto actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    try {
      DB::beginTransaction();
      $this->productRepository->cuid_delete($product->id_products);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('products')->with('error', 'Ocurrió un error al eliminar el producto' . $e->getMessage());
    }
    return redirect()->route('products')->with('success', 'Producto eliminado correctamente');
  }
}
