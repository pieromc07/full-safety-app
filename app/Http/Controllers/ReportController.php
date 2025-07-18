<?php

namespace App\Http\Controllers;

use App\Models\ActivePause;
use App\Models\AlcoholTest;
use App\Models\CheckPoint;
use App\Models\DailyDialog;
use App\Models\Enterprise;
use App\Models\GPSControl;
use App\Models\Inspection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{

  function daily(Request $request)
  {
    $id_checkpoints = $request->id_checkpoints ?? '';
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

    $inspections = Inspection::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseSupplier', 'enterpriseTransport', 'inspectionType', 'targeted', 'user')
      ->orderBy('id_inspections', 'desc')
      ->get();

    $dialogs = DailyDialog::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_daily_dialogs', 'desc')
      ->get();

    $breaks = ActivePause::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_active_pauses', 'desc')
      ->get();

    $alcoholTests = AlcoholTest::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_alcohol_tests', 'desc')
      ->get();

    $gpsControls = GPSControl::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_gps_controls', 'desc')
      ->get();

    $transportEnterprises = Enterprise::where('id_enterprise_types', 2)->get();
    $checkpoints = CheckPoint::all();

    return view('reports.daily', compact('checkpoints', 'transportEnterprises', 'inspections', 'dialogs', 'breaks', 'alcoholTests', 'gpsControls'));
  }


  function dailyPdf(Request $request)
  {
    $id_checkpoints = $request->id_checkpoints ?? '';
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

    $inspections = Inspection::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseSupplier', 'enterpriseTransport', 'inspectionType', 'targeted', 'user')
      ->orderBy('id_inspections', 'desc')
      ->get();

    $dialogs = DailyDialog::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_daily_dialogs', 'desc')
      ->get();

    $breaks = ActivePause::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_active_pauses', 'desc')
      ->get();

    $alcoholTests = AlcoholTest::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_alcohol_tests', 'desc')
      ->get();

    $gpsControls = GPSControl::whereBetween('date', [$start, $end])
      ->when($id_checkpoints, function ($query, $id_checkpoints) {
        return $query->where('id_checkpoints', $id_checkpoints);
      })
      ->when($id_transport_enterprises, function ($query, $id_transport_enterprises) {
        return $query->where('id_transport_enterprises', $id_transport_enterprises);
      })
      ->with('checkPoint', 'enterpriseTransport', 'enterpriseSupplier', 'user')
      ->orderBy('id_gps_controls', 'desc')
      ->get();

    // dd($inspections, $dialogs, $breaks, $alcoholTests, $gpsControls);
    $pdf = Pdf::loadView('reports.dailyPDF', compact('inspections', 'dialogs', 'breaks', 'alcoholTests', 'gpsControls', 'start', 'end'));
    $paper_format = 'A4';
    $type = 'portrait';
    $pdf->setPaper($paper_format, $type);
    return $pdf->stream('reporte_diario' . $start . '-' . $end . '.pdf');
  }
}
