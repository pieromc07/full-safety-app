<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnterpriseRelsEnterprise extends Model
{
  use HasFactory;

  protected $fillable = [
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'cuid_inserted',
    'cuid_updated'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated'
  ];

  public $timestamps = false;

  public function supplierEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises');
  }

  public function transportEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises');
  }

  public static function uniqueSupplierAndTransport($supplierId, $transportId)
  {
    return EnterpriseRelsEnterprise::where('id_supplier_enterprises', $supplierId)->where('id_transport_enterprises', $transportId)->first();
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
