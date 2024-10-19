<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InspectionType;
use App\Models\Targeted;
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
    //
    $categories = Category::where('parent_id', null)->paginate(5);
    $inspectionTypes = InspectionType::all();
    $targeteds = Targeted::where('targeted_id', null)->get();
    return view($this::$viewDir . '.categories', compact('categories', 'inspectionTypes', 'targeteds'));
  }

  public function index1()
  {
    //
    $subcategories = Category::where('parent_id', '<>', null)->paginate(5);
    $categories = Category::where('parent_id', null)->get();
    return view($this::$viewDir . '.category', compact('categories', 'subcategories'));
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
    $validated = $request->validate(Category::$rules, Category::$rulesMessages);
    try {
      DB::beginTransaction();
      $category = new Category();
      $category->name = $validated['name'];
      $category->parent_id = $validated['parent_id'] ?? null;
      $category->targeted_id = $validated['targeted_id'] ?? null;
      $category->inspection_type_id = $validated['inspection_type_id'] ?? null;
      $category->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['parent_id']) {
        return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar crear la subcategoría.');
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar crear la categoría.');
    }
    if ($validated['parent_id']??false) {
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
      $category->targeted_id = $validated['targeted_id'] ?? null;
      $category->inspection_type_id = $validated['inspection_type_id'] ?? null;
      $category->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['parent_id']) {
        return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar actualizar la subcategoría.');
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar actualizar la categoría.');
    }
    if ($validated['parent_id']??false) {
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
      $category->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($category->parent_id) {
        return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar eliminar la subcategoría.');
      }
      return redirect()->route('category')->with('error', 'Ha ocurrido un error al intentar eliminar la categoría.');
    }
    if ($category->parent_id) {
      return redirect()->route('category')->with('success', 'Subcategoría eliminada correctamente.');
    }
    return redirect()->route('category')->with('success', 'Categoría eliminada correctamente.');
  }
}
