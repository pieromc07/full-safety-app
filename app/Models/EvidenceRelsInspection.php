<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EvidenceRelsInspection extends Model
{
  use HasFactory;

  protected $fillable = [
    'inspection_id',
    'evidence_id',
    'state',
    'evidence_one',
    'evidence_two',
    'observations',
    'waiting_time',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'inspection_id' => 'required|exists:inspections,id',
    'evidence_id' => 'required|exists:evidence,id',
    'state' => 'required|in:1,2,3',
    'evidence_one' => 'nullable',
    'evidence_two' => 'nullable',
    'observations' => 'nullable',
    'waiting_time' => 'nullable'
  ];

  public static $rulesMessages = [
    'inspection_id.required' => 'La inspección es obligatoria.',
    'inspection_id.exists' => 'La inspección no existe.',
    'evidence_id.required' => 'La evidencia es obligatoria.',
    'evidence_id.exists' => 'La evidencia no existe.',
    'state.required' => 'El estado es obligatorio.',
    'state.in' => 'El estado no es válido.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  public function inspection()
  {
    return $this->belongsTo(Inspection::class);
  }

  public function evidence()
  {
    return $this->belongsTo(Evidence::class);
  }

  public function cuidInsertedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_inserted);
  }

  public function cuidUpdatedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_updated);
  }

  public function cuidToDatetime($cuid)
  {
    return DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid])->datetime;
  }
}
