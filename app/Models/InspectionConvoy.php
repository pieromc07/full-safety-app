<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InspectionConvoy extends Model
{
  use HasFactory;

  protected $table = 'inspection_convoys';

  protected $fillable = [
    'inspection_id',
    'convoy',
    'convoy_status',
    'quantity_light_units',
    'quantity_heavy_units',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'inspection_id' => 'required|exists:inspections,id',
    'convoy' => 'nullable',
    'convoy_status' => 'nullable',
    'quantity_light_units' => 'nullable',
    'quantity_heavy_units' => 'nullable',
  ];

  public static $rulesMessages = [
    'inspection_id.required' => 'La inspección es requerida.',
    'inspection_id.exists' => 'La inspección no existe.',
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
