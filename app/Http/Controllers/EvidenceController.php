<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Evidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvidenceController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $evidences = Evidence::paginate(5);
    $subcategories = Category::where('parent_id', '<>', null)->get();
    return view($this::$viewDir . '.evidences', compact('evidences', 'subcategories'));
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
    $validated = $request->validate(Evidence::$rules, Evidence::$rulesMessages);
    try {
      DB::beginTransaction();
      $subcategory = Category::find($validated['subcategory_id']) ?? null;
      $evidence = new Evidence();
      $evidence->name = $validated['name'];
      $evidence->description = $validated['description'] ?? null;
      $evidence->category_id = $subcategory->parent_id ?? null;
      $evidence->subcategory_id = $validated['subcategory_id'];
      $evidence->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('evidence')->with('error', 'Error al guardar la evidencia.');
    }
    return redirect()->route('evidence')->with('success', 'Evidencia guardada correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Evidence $evidence)
  {
    return view($this::$viewDir . '.show', compact('evidence'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Evidence $evidence)
  {
    return view($this::$viewDir . '.edit', compact('evidence'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Evidence $evidence)
  {
    $validated = $request->validate(Evidence::$rules, Evidence::$rulesMessages);
    try {
      DB::beginTransaction();
      $subcategory = Category::find($validated['subcategory_id']) ?? null;
      $evidence->name = $validated['name'];
      $evidence->description = $validated['description'] ?? null;
      $evidence->category_id = $subcategory->parent_id ?? null;
      $evidence->subcategory_id = $validated['subcategory_id'];
      $evidence->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('evidence')->with('error', 'Error al actualizar la evidencia.');
    }
    return redirect()->route('evidence')->with('success', 'Evidencia actualizada correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Evidence $evidence)
  {
    try {
      DB::beginTransaction();
      $evidence->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('evidence')->with('error', 'Error al eliminar la evidencia.');
    }
    return redirect()->route('evidence')->with('success', 'Evidencia eliminada correctamente.');
  }
}
