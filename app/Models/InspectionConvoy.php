<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InspectionConvoy extends Model
{
  use HasFactory;

  protected $table = 'inspection_convoys';

  protected $primaryKey = 'id_inspection_convoys';

  protected $fillable = [
    'id_inspections',
    'convoy',
    'convoy_status',
    'quantity_light_units',
    'quantity_heavy_units',
    'id_products',
    'id_products_two',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'id_inspections' => 'required|exists:inspections,id',
    'convoy' => 'nullable',
    'convoy_status' => 'nullable',
    'quantity_light_units' => 'nullable',
    'quantity_heavy_units' => 'nullable',
    'id_products' => 'nullable',
    'id_products_two' => 'nullable',
  ];

  public static $rulesMessages = [
    'id_inspections.required' => 'La inspección es requerida.',
    'id_inspections.exists' => 'La inspección no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  public function inspection()
  {
    return $this->belongsTo(Inspection::class);
  }

  public function cuidInsertedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_inserted);
  }

  public function cuidUpdatedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_updated);
  }

  private function cuidToDatetime($cuid)
  {
    return DB::select('SELECT FROM_UNIXTIME(?) as datetime', [$cuid])[0]->datetime;
  }

  public function getConvoyStatusAttribute($value)
  {
    return $value == 1 ? 'Bajada' : 'Subida';
  }
}
