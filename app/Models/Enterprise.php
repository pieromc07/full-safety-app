<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Enterprise extends Model
{
  use HasFactory;

  protected $table = 'enterprises';

  protected $primaryKey = 'id_enterprises';

  protected $fillable = [
    'name',
    'ruc',
    'image',
    'id_enterprise_types',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'ruc' => 'required|size:11',
    'image' => 'nullable',
    'id_enterprise_types' => 'required|exists:enterprise_types,id_enterprise_types'
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'ruc.required' => 'El RUC es obligatorio.',
    'ruc.size' => 'El RUC debe tener 11 caracteres.',
    'id_enterprise_types.required' => 'El tipo de empresa es obligatorio.',
    'id_enterprise_types.exists' => 'El tipo de empresa no existe.'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  public function enterpriseType()
  {
    return $this->belongsTo(EnterpriseType::class, 'id_enterprise_types', 'id_enterprise_types');
  }

  public function transportEnterprises()
  {
    // Mostar empresas de transporte que estan en la tabla de relaciones
    return Enterprise::join('enterprise_rels_enterprises', 'enterprise_rels_enterprises.id_transport_enterprises', '=', 'enterprises.id_enterprises')
      ->join('enterprise_types', 'enterprises.id_enterprise_types', '=', 'enterprise_types.id_enterprise_types')
      ->where('enterprise_rels_enterprises.id_supplier_enterprises', $this->id_enterprises)
      ->select('enterprises.*', 'enterprise_types.name AS enterprisetype')
      ->get();
  }

  public static function onlyTransportEnterprises()
  {
    // Mostrar solo empresas de transporte
    return Enterprise::join('enterprise_types', 'enterprises.id_enterprise_types', '=', 'enterprise_types.id_enterprise_types')
      ->where('enterprise_types.id_enterprise_types', 2)
      ->select('enterprises.*')
      ->get();
  }

  public static function onlySupplierEnterprises()
  {
    // Mostrar solo empresas proveedoras
    return Enterprise::join('enterprise_types', 'enterprises.id_enterprise_types', '=', 'enterprise_types.id_enterprise_types')
      ->where('enterprise_types.id_enterprise_types', 1)
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
