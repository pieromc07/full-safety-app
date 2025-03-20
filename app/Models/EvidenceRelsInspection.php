<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EvidenceRelsInspection extends Model
{
  use HasFactory;

  protected $table = 'evidence_rels_inspections';

  protected $primaryKey = 'id_evidence_rels_inspections';

  protected $fillable = [
    'id_inspections',
    'id_evidences',
    'state',
    'evidence_one',
    'evidence_two',
    'observations',
    'waiting_time',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'id_inspections' => 'required|exists:inspections,id',
    'id_evidences' => 'required|exists:evidence,id',
    'state' => 'required|in:1,2,3',
    'evidence_one' => 'nullable',
    'evidence_two' => 'nullable',
    'observations' => 'nullable',
    'waiting_time' => 'nullable'
  ];

  public static $rulesMessages = [
    'id_inspections.required' => 'La inspección es obligatoria.',
    'id_inspections.exists' => 'La inspección no existe.',
    'id_evidences.required' => 'La evidencia es obligatoria.',
    'id_evidences.exists' => 'La evidencia no existe.',
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
    return $this->belongsTo(Inspection::class, 'id_inspections', 'id_inspections');
  }

  public function evidence()
  {
    return $this->belongsTo(Evidence::class, 'id_evidences', 'id_evidences');
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
