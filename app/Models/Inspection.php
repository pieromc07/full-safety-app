<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inspection extends Model
{
  use HasFactory;

  protected $table = 'inspections';

  protected $primaryKey = 'id_inspections';

  protected $fillable = [
    'correlative',
    'year',
    'folio',
    'date',
    'hour',
    'id_inspection_types',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'id_checkpoints',
    'id_targeteds',
    'id_users',
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
    'id_inspection_types' => 'required|exists:inspection_types,id',
    'id_supplier_enterprises' => 'required|exists:enterprises,id',
    'id_transport_enterprises' => 'required|exists:enterprises,id',
    'id_checkpoints' => 'required|exists:checkpoints,id',
    'id_targeteds' => 'required|exists:targeteds,id',
    'id_users' => 'required|exists:users,id',
    'observation' => 'nullable'
  ];

  public static $rulesMessages = [
    'correlative.required' => 'El correlativo es obligatorio.',
    'year.required' => 'El año es obligatorio.',
    'folio.required' => 'El folio es obligatorio.',
    'date.date' => 'La fecha no es válida.',
    'hour.date_format' => 'La hora no es válida.',
    'id_inspection_types.required' => 'El tipo de inspección es obligatorio.',
    'id_inspection_types.exists' => 'El tipo de inspección no existe.',
    'id_supplier_enterprises.required' => 'La empresa proveedora es obligatoria.',
    'id_supplier_enterprises.exists' => 'La empresa proveedora no existe.',
    'id_transport_enterprises.required' => 'La empresa de transporte es obligatoria.',
    'id_transport_enterprises.exists' => 'La empresa de transporte no existe.',
    'id_checkpoints.required' => 'El punto de control es obligatorio.',
    'id_checkpoints.exists' => 'El punto de control no existe.',
    'id_targeteds.required' => 'El objetivo es obligatorio.',
    'id_targeteds.exists' => 'El objetivo no existe.',
    'id_users.required' => 'El usuario es obligatorio.',
    'id_users.exists' => 'El usuario no existe.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  // Relationships
  public function inspectionType()
  {
    return $this->belongsTo(InspectionType::class, 'id_inspection_types', 'id_inspection_types');
  }

  public function enterpriseSupplier()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises', 'id_enterprises');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises', 'id_enterprises');
  }

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class, 'id_checkpoints', 'id_checkpoints');
  }

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'id_targeteds', 'id_targeteds');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id_users', 'id_users');
  }

  public function evidences()
  {
    return $this->hasMany(EvidenceRelsInspection::class, 'id_inspections', 'id_inspections');
  }

  public function convoy()
  {
    return $this->hasOne(InspectionConvoy::class, 'id_inspections', 'id_inspections');
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
