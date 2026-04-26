<?php

namespace App\Http\Controllers;

use App\Mail\LifReport;
use App\Models\CheckPoint;
use App\Models\Enterprise;
use App\Models\EvidenceRelsInspection;
use App\Models\Inspection;
use App\Models\InspectionConvoy;
use App\Models\Product;
use App\Models\Targeted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $type = $request->input('type') ?? 1;
    $inspections = Inspection::where('id_inspection_types', $type)->orderBy('id_inspections', 'desc')->paginate(10);
    if ($type == 1) {
      return view('inspections.operative.index', compact('inspections'));
    }
    return view('inspections.documentary.index', compact('inspections'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      // 'id_inspection_evidence' => 'required|integer',
      'description'           => 'required|string',
      'date'                  => 'required|date',
      'evidence_one'          => 'required|image|mimes:jpg,jpeg,png|max:4096',
      'evidence_two'          => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ]);

    try {
      $imgOne = base64_encode(file_get_contents($request->file('evidence_one')));
      $imgOne = 'data:image/jpeg;base64,' . $imgOne;

      $imgTwo = null;
      if ($request->hasFile('evidence_two')) {
        $imgTwo = base64_encode(file_get_contents($request->file('evidence_two')));
        $imgTwo = 'data:image/jpeg;base64,' . $imgTwo;
      }
      Mail::to(config('app.report_email', 'admin@example.com'))->send(
        new LifReport(
          // $validated['id_inspection_evidence'],
          $validated['description'],
          $validated['date'],
          $imgOne,
          $imgTwo

        )
      );

      return response()->json([
        'message' => 'Correo enviado correctamente'
      ]);
    } catch (\Exception $e) {

      return response()->json([
        'message' => 'Error al enviar el correo',
        'error'   => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Inspection $inspection)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    $targeteds = Targeted::all();
    $products = Product::all();
    return view('inspections.show', compact('inspection', 'checkpoints', 'transports', 'suppliers', 'targeteds', 'products'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Inspection $inspection)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    $targeteds = Targeted::all();
    $products = Product::join('product_enterprises', 'products.id_products', '=', 'product_enterprises.id_products')
      ->where('id_supplier_enterprises', $inspection->id_supplier_enterprises)
      ->where('id_transport_enterprises', $inspection->id_transport_enterprises)
      ->get();
    return view('inspections.edit', compact('inspection', 'checkpoints', 'transports', 'suppliers', 'targeteds', 'products'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Inspection $inspection)
  {
    $request->merge(['id_users' => Auth::user()->id_users]);
    $request->merge(['id_inspection_types' => $inspection->id_inspection_types]);
    $request->validate(Inspection::$rules, Inspection::$messages);
    try {
      DB::beginTransaction();
      $inspection->update([
        'date' => $request->date,
        'hour' => $request->hour,
        'id_inspection_types' => $request->id_inspection_types,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'id_checkpoints' => $request->id_checkpoints,
        'id_targeteds' => $request->id_targeteds,
        'id_users' => $request->id_users,
      ]);
      InspectionConvoy::where('id_inspections', $inspection->id_inspections)->update(
        [
          'convoy' => $request->convoy,
          'convoy_status' => $request->convoy_status,
          'quantity_light_units' => $request->quantity_light_units,
          'quantity_heavy_units' => $request->quantity_heavy_units,
          'id_products' => $request->id_products,
          'id_products_two' => $request->id_products_two,
        ]
      );
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('inspections.edit', $inspection->id_inspections)->with('error', 'Ocurrió un error al actualizar el inspeccion' . $e->getMessage())->withInput();
    }
    return redirect()->back()->with('success', 'Inspeccion actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Inspection $inspection)
  {
    try {
      DB::beginTransaction();
      $this::softDeleteWhere('evidence_rels_inspections', 'id_inspections', $inspection->id_inspections);
      $this::softDeleteWhere('inspection_convoys', 'id_inspections', $inspection->id_inspections);
      $this::softDelete($inspection);
      DB::commit();
      return redirect()->route('inspections')->with('success', 'Inspeccion Eliminada');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('inspections')->with('error', 'Ocurrió un error al eliminar el inspeccion' . $e->getMessage());
    }
  }

  // En app/Http/Controllers/InspectionController.php
  public function report(Inspection $inspection)
  {
    $checkpoints = CheckPoint::all();
    $transports = Enterprise::where('id_enterprise_types', 2)->get();
    $suppliers = Enterprise::where('id_enterprise_types', 1)->get();
    $targeteds = Targeted::all();
    $products = Product::all();
    return view('inspections.operative.report', compact('inspection', 'checkpoints', 'transports', 'suppliers', 'targeteds', 'products'));
  }
}
