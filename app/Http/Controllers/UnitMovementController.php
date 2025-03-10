<?php

namespace App\Http\Controllers;

use App\Models\CheckPoint;
use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\UnitMovement;
use App\Models\UnitMovementDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitMovementController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

    $id_checkpoints = $request->id_checkpoints ?? '';
    $direction = $request->direction ?? '';
    $convoy_state = $request->convoy_state ?? '';
    $id_transport_enterprises = $request->id_transport_enterprises ?? '';
    if ($request->rangeDate == null || $request->rangeDate == '') {
      $request->merge(['rangeDate' => $request->session()->get('rangeDate') ?? date('Y-m-d') . ' 00:00:00 - ' . date('Y-m-d') . ' 23:59:59']);
    } else {
      $request->session()->put('rangeDate', $request->rangeDate);
    }

    $rangeDate = $request->rangeDate ?? '';
    if ($rangeDate != '') {
      $dates = explode(' - ', $rangeDate);
      $start = date('Y-m-d H:i:s', strtotime($dates[0]));
      $end = date('Y-m-d H:i:s', strtotime($dates[1]));
    } else {
      $start = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' 00:00:00'));
      $end = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' 23:59:59'));
    }

    $unitmovements = UnitMovement::with('checkPoint', 'supplierEnterprise', 'transportEnterprise', 'product', 'unitMovementDetails')
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($direction, function ($query, $direction) {
        return $query->where('direction', $direction);
      })
      ->when($convoy_state, function ($query, $convoy_state) {
        return $query->where('convoy_state', $convoy_state);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->whereBetween('date', [$start, $end])
      ->orderBy('id_unit_movements', 'desc')
      ->paginate(self::LARGETAKE);
    $transportEnterprises = Enterprise::where('id_enterprise_types', 2)->get();
    $checkpoints = CheckPoint::all();

    return view('movements.index', compact('unitmovements', 'checkpoints', 'transportEnterprises'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $checkpoints = CheckPoint::all();
    $supplierEnterprises = Enterprise::where('id_enterprise_types', 1)->get();
    return view('movements.create', compact('checkpoints', 'supplierEnterprises'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {

    $request->validate(UnitMovement::$rules, UnitMovement::$messages);
    try {
      DB::beginTransaction();
      $unitmovement =  UnitMovement::create([
        'date' => $request->date,
        'time' => date('H:i:s', strtotime($request->date)),
        'time_arrival' => $request->time_arrival ?? NULL,
        'time_departure' => $request->time_departure ?? NULL,
        'id_checkpoints' => $request->id_checkpoints,
        'convoy' => $request->convoy,
        'convoy_state' => $request->convoy_state,
        'direction' => $request->direction,
        'heavy_vehicle' => $request->heavy_vehicle,
        'light_vehicle' => $request->light_vehicle,
        'id_supplier_enterprises' => $request->id_supplier_enterprises,
        'id_transport_enterprises' => $request->id_transport_enterprises,
        'id_products' => $request->id_products,
        'id_users' => auth()->user()->id_users,
      ]);
      $weight = $request->weight;
      $id_products_two = $request->id_products_two;
      $weight_two = $request->weight_two;
      $id_units = $request->id_units;
      $guide = $request->guide;

      for ($i = 0; $i < count($weight); $i++) {
        UnitMovementDetail::create([
          'id_unit_movements' => $unitmovement->id_unit_movements,
          'weight' => $weight[$i],
          'id_units' => $id_units[$i],
          'id_products_two' => $id_products_two[$i],
          'weight_two' => $weight_two[$i],
          'referral_guide' => $guide[$i]
        ]);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('unitmovements.create')->with('error', 'Ha ocurrido un error al intentar crear el movimiento. ' . $e->getMessage())->withInput();
    }
    return redirect()->route('unitmovements.create')->with('success', 'El movimiento se ha creado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(UnitMovement $unitMovement)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(UnitMovement $unitMovement)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, UnitMovement $unitMovement)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(UnitMovement $unitMovement)
  {
    //
  }

  public function exportPdf(Request $request)
  {


    $id_checkpoints = $request->id_checkpoints ?? '';
    $direction = $request->direction ?? '';
    $convoy_state = $request->convoy_state ?? '';
    $id_transport_enterprises = $request->id_transport_enterprises ?? '';
    $rangeDate = $request->rangeDate ?? '';
    if ($rangeDate != '') {
      $dates = explode(' - ', $rangeDate);
      $start = date('Y-m-d H:i:s', strtotime($dates[0]));
      $end = date('Y-m-d H:i:s', strtotime($dates[1]));
    } else {
      $start = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' 00:00:00'));
      $end = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' 23:59:59'));
    }

    $unitmovements = UnitMovement::with('checkPoint', 'supplierEnterprise', 'transportEnterprise', 'product', 'unitMovementDetails')
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($direction, function ($query, $direction) {
        return $query->where('direction', $direction);
      })
      ->when($convoy_state, function ($query, $convoy_state) {
        return $query->where('convoy_state', $convoy_state);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->whereBetween('date', [$start, $end])
      ->orderBy('id_unit_movements', 'desc')
      ->get();

    $supplierEnterprise = EnterpriseRelsEnterprise::where('id_transport_enterprises', $id_transport_enterprises)->first()->supplierEnterprise->name ?? '';

    $title = $supplierEnterprise . ' - ' . ($direction == 1 ? 'Subida' : ($direction == 2 ? 'Bajada' : 'Ambas direcciones')) . ' - ' . ($convoy_state == 1 ? 'Cargado' : ($convoy_state == 2 ? 'Vacio' : 'Ambos estados'));
    $dates = 'Del ' .  date('d-m-Y', strtotime($start)) . ' al ' . date('d-m-Y', strtotime($end));
    $pdf = Pdf::loadView('movements.report', compact('unitmovements', 'title', 'dates'));
    $paper_format = 'a4';
    $type = 'portrait';
    $pdf->setPaper($paper_format, $type);
    return $pdf->stream('reporte_movimientos.pdf');
  }
}
