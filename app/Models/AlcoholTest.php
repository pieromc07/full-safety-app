<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AlcoholTest extends Model
{
  use HasFactory;

  protected $fillable = [
    'date',
    'hour',
    'checkpoint_id',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'employee_id',
    'result',
    'state',
    'photo_one',
    'photo_one_uri',
    'photo_two',
    'photo_two_uri',
    'cuid_inserted',
    'cuid_updated'
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated'];

  public $timestamps = false;

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class);
  }

  public function enterpriseSupplier()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises');
  }

  public function employee()
  {
    return $this->belongsTo(Employee::class);
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
    $result = DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid]);
    return $result ? $result->datetime : null;
  }
}
