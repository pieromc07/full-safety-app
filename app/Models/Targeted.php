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
    'targeted_id',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'image' => 'nullable',
    'targeted_id' => 'nullable|exists:targeteds,id_targeteds',
    'id_inspection_types' => 'nullable|array',
    'id_inspection_types.*' => 'exists:inspection_types,id_inspection_types',
    'id_load_types' => 'nullable|array',
    'id_load_types.*' => 'exists:load_types,id_load_types',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'targeted_id.exists' => 'El target no existe.',
    'id_inspection_types.*.exists' => 'El tipo de inspección no existe.',
    'id_load_types.*.exists' => 'El tipo de carga no existe.',
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

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'targeted_id', 'id_targeteds');
  }

  public function targetedRelsInspections()
  {
    return $this->hasMany(TargetedRelsInspection::class, 'id_targeteds', 'id_targeteds');
  }

  public function targetedRelsLoadTypes()
  {
    return $this->hasMany(TargetedRelsLoadType::class, 'id_targeteds', 'id_targeteds');
  }

  public function cuidInsertedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_inserted);
  }

  public function cuidUpdatedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_updated);
  }

  public function categories()
  {
    return $this->hasManyThrough(
      Category::class,
      TargetedRelsInspection::class,
      'id_targeteds',                  // FK en pivot que apunta a Targeted
      'id_targeted_rels_inspections',  // FK en categories que apunta a pivot
      'id_targeteds',                  // PK local en Targeted
      'id_targeted_rels_inspections'   // PK local en pivot
    );
  }


  public function cuidToDatetime($cuid)
  {
    return DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid])->datetime;
  }
}
