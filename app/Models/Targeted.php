<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Targeted extends Model
{
  use HasFactory;

  protected $table = 'targeteds';

  protected $primaryKey = 'id_targeteds';

  protected $fillable = [
    'name',
    'image',
    'id_load_types',
    'targeted_id',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'image' => 'nullable',
    'id_load_types' => 'nullable|exists:load_types,id_load_types',
    'targeted_id' => 'nullable|exists:targeteds,id_targeteds',
    'id_inspection_types' => 'nullable|array',
    'id_inspection_types.*' => 'exists:inspection_types,id_inspection_types',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'targeted_id.exists' => 'El target no existe.',
    'id_inspection_types.exists' => 'El tipo de inspección no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public function targeteds()
  {
    return $this->hasMany(Targeted::class, 'targeted_id', 'id_targeteds');
  }

  public function loadType()
  {
    return $this->belongsTo(LoadType::class, 'id_load_types', 'id_load_types');
  }

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'targeted_id', 'id_targeteds');
  }

  public function targetedRelsInspections()
  {
    return $this->hasMany(TargetedRelsInspection::class, 'id_targeteds', 'id_targeteds');
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
