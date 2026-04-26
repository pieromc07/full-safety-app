<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Evidence;
use App\Models\TargetedRelsInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $categories = Category::with('targetedRelsInspection.targeted', 'targetedRelsInspection.inspectionType')
      ->whereNull('parent_id')
      ->paginate(self::MEDIUMTAKE);
    $targetedRelsInspections = TargetedRelsInspection::with('targeted', 'inspectionType')->get();
    return view($this::$viewDir . '.categories', compact('categories', 'targetedRelsInspections'));
  }

  public function index1()
  {
    //
    $subcategories = Category::where('parent_id', '<>', null)->paginate(10);
    $categories = Category::where('parent_id', null)->get();
    return view($this::$viewDir . '.category', compact('categories', 'subcategories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate(Category::$rules, Category::$rulesMessages);
    try {
      DB::beginTransaction();
      $category = new Category();
      $category->name = $validated['name'];
      $category->parent_id = $validated['parent_id'] ?? null;
      // Solo categorías raíz llevan el par; las subcategorías heredan del padre.
      $category->id_targeted_rels_inspections = $category->parent_id
        ? null
        : ($validated['id_targeted_rels_inspections'] ?? null);
      $category->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['parent_id']) {
        return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar crear la subcategoría ' . $e->getMessage());
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar crear la categoría. ' . $e->getMessage());
    }
    if ($validated['parent_id'] ?? false) {
      return redirect()->route('category1')->with('success', 'Subcategoría creada correctamente.');
    }
    return redirect()->route('category')->with('success', 'Categoría creada correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category)
  {
    return view($this::$viewDir . '.show', compact('category'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category)
  {
    return view($this::$viewDir . '.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Category $category)
  {
    $validated = $request->validate(Category::$rules, Category::$rulesMessages);
    try {
      DB::beginTransaction();
      $category->name = $validated['name'];
      $category->parent_id = $validated['parent_id'] ?? null;
      $category->id_targeted_rels_inspections = $category->parent_id
        ? null
        : ($validated['id_targeted_rels_inspections'] ?? null);
      $category->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['parent_id']) {
        return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar actualizar la subcategoría. ' . $e->getMessage());
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar actualizar la categoría. ' . $e->getMessage());
    }
    if ($validated['parent_id'] ?? false) {
      return redirect()->route('category1')->with('success', 'Subcategoría actualizada correctamente.');
    }
    return redirect()->route('category')->with('success', 'Categoría actualizada correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    try {
      DB::beginTransaction();
      $childCount = Category::where('parent_id', $category->id_categories)->whereNull('cuid_deleted')->count();
      if ($childCount > 0) {
        $route = $category->parent_id ? 'category1' : 'category';
        return redirect()->route($route)->with('error', 'No se puede eliminar porque tiene subcategorías asociadas.');
      }
      $evidenceCount = Evidence::where('id_subcategories', $category->id_categories)->whereNull('cuid_deleted')->count();
      if ($evidenceCount > 0) {
        $route = $category->parent_id ? 'category1' : 'category';
        return redirect()->route($route)->with('error', 'No se puede eliminar porque tiene evidencias asociadas.');
      }
      $this::softDelete($category);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($category->parent_id) {
        return redirect()->route('category1')->with('error', 'Ha ocurrido un error al intentar eliminar la subcategoría.');
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar eliminar la categoría.');
    }
    if ($category->parent_id) {
      return redirect()->route('category1')->with('success', 'Subcategoría eliminada correctamente.');
    }
    return redirect()->route('category')->with('success', 'Categoría eliminada correctamente.');
  }
}
