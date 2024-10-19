<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Enterprise extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'ruc',
    'image',
    'enterprise_type_id',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'ruc' => 'required|size:11',
    'image' => 'nullable',
    'enterprise_type_id' => 'required|exists:enterprise_types,id'
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'ruc.required' => 'El RUC es obligatorio.',
    'ruc.size' => 'El RUC debe tener 11 caracteres.',
    'enterprise_type_id.required' => 'El tipo de empresa es obligatorio.',
    'enterprise_type_id.exists' => 'El tipo de empresa no existe.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  public function enterpriseType()
  {
    return $this->belongsTo(EnterpriseType::class);
  }

  public function transportEnterprises()
  {
    // Mostar empresas de transporte que estan en la tabla de relaciones
    return Enterprise::join('enterprise_rels_enterprises', 'enterprise_rels_enterprises.transport_enterprise_id', '=', 'enterprises.id')
      ->join('enterprise_types', 'enterprises.enterprise_type_id', '=', 'enterprise_types.id')
      ->where('enterprise_rels_enterprises.supplier_enterprise_id', $this->id)
      ->select('enterprises.*', 'enterprise_types.name AS enterprisetype')
      ->get();
  }

  public static function onlyTransportEnterprises()
  {
    // Mostrar solo empresas de transporte
    return Enterprise::join('enterprise_types', 'enterprises.enterprise_type_id', '=', 'enterprise_types.id')
      ->where('enterprise_types.id', 2)
      ->select('enterprises.*')
      ->get();
  }

  public static function onlySupplierEnterprises()
  {
    // Mostrar solo empresas proveedoras
    return Enterprise::join('enterprise_types', 'enterprises.enterprise_type_id', '=', 'enterprise_types.id')
      ->where('enterprise_types.id', 1)
      ->select('enterprises.*')
      ->get();
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
