<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inspection extends Model
{
  use HasFactory;

  protected $fillable = [
    'date',
    'hour',
    'inspection_type_id',
    'enterprise_id',
    'checkpoint_id',
    'targeted_id',
    'observation',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'date' => 'nullable|date',
    'hour' => 'nullable|date_format:H:i',
    'inspection_type_id' => 'required|exists:inspection_types,id',
    'enterprise_id' => 'required|exists:enterprises,id',
    'checkpoint_id' => 'required|exists:check_points,id',
    'targeted_id' => 'required|exists:targeteds,id',
    'observation' => 'nullable'
  ];

  public static $rulesMessages = [
    'date.date' => 'La fecha no es válida.',
    'hour.date_format' => 'La hora no es válida.',
    'inspection_type_id.required' => 'El tipo de inspección es obligatorio.',
    'inspection_type_id.exists' => 'El tipo de inspección no existe.',
    'enterprise_id.required' => 'La empresa es obligatoria.',
    'enterprise_id.exists' => 'La empresa no existe.',
    'checkpoint_id.required' => 'El punto de control es obligatorio.',
    'checkpoint_id.exists' => 'El punto de control no existe.',
    'targeted_id.required' => 'El objetivo es obligatorio.',
    'targeted_id.exists' => 'El objetivo no existe.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  public function inspectionType()
  {
    return $this->belongsTo(InspectionType::class);
  }

  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class);
  }

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class);
  }

  public function targeted()
  {
    return $this->belongsTo(Targeted::class);
  }

  public function evidences()
  {
    return $this->hasMany(EvidenceRelsInspection::class);
  }

  public function convoy()
  {
    return $this->hasOne(InspectionConvoy::class);
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
