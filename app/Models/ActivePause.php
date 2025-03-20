<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivePause extends Model
{
  use HasFactory;

  protected $table = 'active_pauses';

  protected $primaryKey = 'id_active_pauses';

  protected $fillable = [
    'date',
    'hour',
    'id_checkpoints',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'participants',
    'photo_one',
    'photo_two',
    'id_users',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated'];

  public $timestamps = false;

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class, 'id_checkpoints', 'id_checkpoints');
  }

  public function enterpriseSupplier()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises', 'id_enterprises');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises', 'id_enterprises');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id_users', 'id_users');
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
