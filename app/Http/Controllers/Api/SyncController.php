<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CheckPoint;
use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\EnterpriseType;
use App\Models\Evidence;
use App\Models\InspectionType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use Illuminate\Http\Request;

class SyncController extends Controller
{

  public function sync()
  {
    $inspectionsType = InspectionType::all();
    $enterprisesType = EnterpriseType::all();
    $enterprises = Enterprise::all();
    $enterprisesRelsEnterprise = EnterpriseRelsEnterprise::all();
    $categories = Category::where('parent_id', null)->get();
    $subcategories = Category::where('parent_id', '!=', null)->get();
    $targeteds = Targeted::all();
    $evidences = Evidence::all();
    $checkPoints = CheckPoint::all();
    $targetedsRelsInspections = TargetedRelsInspection::all();

    return response()->json([
      'inspectionsType' => $inspectionsType,
      'enterprisesType' => $enterprisesType,
      'enterprises' => $enterprises,
      'enterprisesRelsEnterprise' => $enterprisesRelsEnterprise,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'evidences' => $evidences,
      'targeteds' => $targeteds,
      'targetedRelsInspections' => $targetedsRelsInspections,
      'checkPoints' => $checkPoints
    ], 200);
  }
}
