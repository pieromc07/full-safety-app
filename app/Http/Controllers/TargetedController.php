<?php

namespace App\Http\Controllers;

use App\Models\InspectionType;
use App\Models\LoadType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetedController extends Controller
{

  static $viewDir = 'maintenance';
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $targeteds = Targeted::where('targeted_id', null)->paginate(5);
    $inspectionTypes = InspectionType::all();
    $loadTypes = LoadType::all();
    // dd($targeteds[0]->targetedRelsInspections);
    return view($this::$viewDir . '.targeteds', compact('targeteds', 'inspectionTypes', 'loadTypes'));
  }

  public function index1()
  {
    //
    $targets = Targeted::where('targeted_id', '<>', null)->paginate(5);
    $targeteds = Targeted::where('targeted_id', null)->get();
    return view($this::$viewDir . '.targeted', compact('targeteds', 'targets'));
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
    $validated = $request->validate(Targeted::$rules, Targeted::$rulesMessages);
    try {
      DB::beginTransaction();
      $targeted = new Targeted();
      $targeted->name = $validated['name'];
      $targeted->id_load_types = $validated['id_load_types'] ?? null;
      if ($request->hasFile('image')) {
        $targeted->image = $this::saveImage($request->file('image'), 'targeteds');
      }
      $targeted->targeted_id = $validated['targeted_id'] ?? null;
      $targeted->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['targeted_id'] ?? null) {
        return redirect()->route('target')->with('error', 'Ha ocurrido un error al intentar crear el Tipo de dirigido.');
      }
      return redirect()->route('targeted')->with('error', 'Ha ocurrido un error al intentar crear el dirigido.');
    }
    if ($validated['targeted_id'] ?? null) {
      return redirect()->route('target')->with('success', 'El Tipo de dirigido se ha creado correctamente.');
    }
    return redirect()->route('targeted')->with('success', 'El dirigido se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Targeted $targeted)
  {

    return view($this::$viewDir . '.show', compact('targeted'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Targeted $targeted)
  {
    return view($this::$viewDir . '.edit', compact('targeted'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Targeted $targeted)
  {
    $validated = $request->validate(Targeted::$rules, Targeted::$rulesMessages);
    try {
      DB::beginTransaction();
      $targeted->name = $validated['name'];
      $targeted->id_load_types = $validated['id_load_types'] ?? null;
      if ($request->hasFile('image')) {
        if ($this::dropImage($targeted->image)) {
          $targeted->image = $this::saveImage($request->file('image'), 'targeteds');
        }
      }
      $targeted->targeted_id = $validated['targeted_id'] ?? null;
      $targeted->save();

      // Eliminar relaciones anteriores
      TargetedRelsInspection::where('id_targeteds', $targeted->id_targeteds)->delete();

      // Crear nuevas relaciones
      if (!empty($validated['id_inspection_types'])) {
        foreach ($validated['id_inspection_types'] as $typeId) {
          TargetedRelsInspection::create([
            'id_targeteds' => $targeted->id_targeteds,
            'id_inspection_types' => $typeId,
          ]);
        }
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      if ($validated['targeted_id'] ?? null) {
        return redirect()->route('target')->with('error', 'Ha ocurrido un error al intentar actualizar el Tipo de dirigido. ' . $e->getMessage());
      }
      return redirect()->route('targeted')->with('error', 'Ha ocurrido un error al intentar actualizar el dirigido. ' . $e->getMessage());
    }
    if ($validated['targeted_id'] ?? null) {
      return redirect()->route('target')->with('success', 'El Tipo de dirigido se ha actualizado correctamente. ' . $targeted->name);
    }
    return redirect()->route('targeted')->with('success', 'El dirigido se ha actualizado correctamente. ' . $targeted->name);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Targeted $targeted)
  {
    try {
      DB::beginTransaction();
      $targeted->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('targeted')->with('error', 'Ha ocurrido un error al intentar eliminar el dirigido.');
    }
    return redirect()->route('targeted')->with('success', 'El dirigido se ha eliminado correctamente.');
  }
}
