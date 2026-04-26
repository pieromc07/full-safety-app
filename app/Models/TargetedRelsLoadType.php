<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TargetedRelsLoadType extends Model
{
  use HasFactory;

  protected $table = 'targeted_rels_load_types';

  protected $primaryKey = 'id_targeted_rels_load_types';

  protected $fillable = [
    'id_targeteds',
    'id_load_types',
    'cuid_inserted',
    'cuid_updated',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'id_targeteds', 'id_targeteds');
  }

  public function loadType()
  {
    return $this->belongsTo(LoadType::class, 'id_load_types', 'id_load_types');
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
