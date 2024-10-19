<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnterpriseRelsEnterprise extends Model
{
  use HasFactory;

  protected $fillable = [
    'supplier_enterprise_id',
    'transport_enterprise_id',
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
    return $this->belongsTo(Enterprise::class, 'supplier_enterprise_id');
  }

  public function transportEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'transport_enterprise_id');
  }

  public static function uniqueSupplierAndTransport($supplierId, $transportId)
  {
    return EnterpriseRelsEnterprise::where('supplier_enterprise_id', $supplierId)->where('transport_enterprise_id', $transportId)->first();
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
