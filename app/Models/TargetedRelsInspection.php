<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TargetedRelsInspection extends Model
{
  use HasFactory;

  protected $table = 'targeted_rels_inspections';

  protected $primaryKey = 'id_targeted_rels_inspections';

  protected $fillable = [
    'id_targeteds',
    'id_inspection_types',
    'cuid_inserted',
    'cuid_updated',
  ];

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'id_targeteds', 'id_targeteds');
  }

  public function inspectionType()
  {
    return $this->belongsTo(InspectionType::class, 'id_inspection_types', 'id_inspection_types');
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
