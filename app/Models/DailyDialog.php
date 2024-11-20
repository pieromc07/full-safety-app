<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DailyDialog extends Model
{
  use HasFactory;

  protected $fillable = [
    'date',
    'hour',
    'checkpoint_id',
    'supplier_enterprise_id',
    'transport_enterprise_id',
    'topic',
    'participants',
    'photo_one',
    'photo_two',
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
    return $this->belongsTo(Enterprise::class, 'supplier_enterprise_id');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'transport_enterprise_id');
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
