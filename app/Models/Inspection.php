<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inspection extends Model
{
  use HasFactory;

  protected $fillable = [
    'correlative',
    'year',
    'folio',
    'date',
    'hour',
    'inspection_type_id',
    'supplier_enterprise_id',
    'transport_enterprise_id',
    'checkpoint_id',
    'targeted_id',
    'user_id',
    'observation',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'correlative' => 'required|integer',
    'year' => 'required|integer',
    'folio' => 'required|string|max:128',
    'date' => 'nullable|date',
    'hour' => 'nullable|date_format:H:i',
    'inspection_type_id' => 'required|exists:inspection_types,id',
    'supplier_enterprise_id' => 'required|exists:enterprises,id',
    'transport_enterprise_id' => 'required|exists:enterprises,id',
    'checkpoint_id' => 'required|exists:check_points,id',
    'targeted_id' => 'required|exists:targeteds,id',
    'user_id' => 'required|exists:users,id',
    'observation' => 'nullable'
  ];

  public static $rulesMessages = [
    'correlative.required' => 'El correlativo es obligatorio.',
    'year.required' => 'El año es obligatorio.',
    'folio.required' => 'El folio es obligatorio.',
    'date.date' => 'La fecha no es válida.',
    'hour.date_format' => 'La hora no es válida.',
    'inspection_type_id.required' => 'El tipo de inspección es obligatorio.',
    'inspection_type_id.exists' => 'El tipo de inspección no existe.',
    'supplier_enterprise_id.required' => 'La empresa proveedora es obligatoria.',
    'supplier_enterprise_id.exists' => 'La empresa proveedora no existe.',
    'transport_enterprise_id.required' => 'La empresa de transporte es obligatoria.',
    'transport_enterprise_id.exists' => 'La empresa de transporte no existe.',
    'checkpoint_id.required' => 'El punto de control es obligatorio.',
    'checkpoint_id.exists' => 'El punto de control no existe.',
    'targeted_id.required' => 'El objetivo es obligatorio.',
    'targeted_id.exists' => 'El objetivo no existe.',
    'user_id.required' => 'El usuario es obligatorio.',
    'user_id.exists' => 'El usuario no existe.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  // Relationships
  public function inspectionType()
  {
    return $this->belongsTo(InspectionType::class);
  }

  public function enterpriseSupplier()
  {
    return $this->belongsTo(Enterprise::class, 'supplier_enterprise_id');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'transport_enterprise_id');
  }

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class);
  }

  public function targeted()
  {
    return $this->belongsTo(Targeted::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function evidences()
  {
    return $this->hasMany(EvidenceRelsInspection::class);
  }

  public function convoy()
  {
    return $this->hasOne(InspectionConvoy::class);
  }

  // Custom Methods
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
    $result = DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid]);
    return $result ? $result->datetime : null;
  }
}
