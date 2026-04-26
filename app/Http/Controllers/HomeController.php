<?php

namespace App\Http\Controllers;

use App\Models\ActivePause;
use App\Models\AlcoholTest;
use App\Models\DailyDialog;
use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\GPSControl;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index()
  {
    $stats = [
      'inspections' => Inspection::whereNull('cuid_deleted')->count(),
      'dialogues' => DailyDialog::whereNull('cuid_deleted')->count(),
      'pauses' => ActivePause::whereNull('cuid_deleted')->count(),
      'tests' => AlcoholTest::whereNull('cuid_deleted')->count(),
      'gps' => GPSControl::whereNull('cuid_deleted')->count(),
      'enterprises' => Enterprise::whereNull('cuid_deleted')->count(),
      'employees' => Employee::whereNull('cuid_deleted')->count(),
      'users' => User::whereNull('cuid_deleted')->count(),
    ];

    $recentInspections = Inspection::with(['checkpoint', 'enterpriseTransport', 'inspectionType'])
      ->whereNull('cuid_deleted')
      ->orderByDesc('id_inspections')
      ->take(5)
      ->get();

    return view('home', compact('stats', 'recentInspections'));
  }
}
