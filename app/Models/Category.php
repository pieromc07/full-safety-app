<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
  use HasFactory;

  protected $table = 'categories';

  protected $primaryKey = 'id_categories';

  protected $fillable = [
    'name',
    'parent_id',
    'id_targeted_rels_inspections',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:64',
    'parent_id' => 'nullable|exists:categories,id_categories',
    'id_targeted_rels_inspections' => 'nullable|exists:targeted_rels_inspections,id_targeted_rels_inspections',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 64 caracteres.',
    'parent_id.exists' => 'La categoría padre no existe.',
    'id_targeted_rels_inspections.exists' => 'La combinación dirigido / tipo de inspección no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public function parent()
  {
    return $this->belongsTo(Category::class, 'parent_id', 'id_categories');
  }

  public function targetedRelsInspection()
  {
    return $this->belongsTo(TargetedRelsInspection::class, 'id_targeted_rels_inspections', 'id_targeted_rels_inspections');
  }

  /**
   * Devuelve la fila pivot efectiva: la propia si está seteada,
   * o la heredada del padre (caso subcategoría).
   */
  public function effectivePair()
  {
    return $this->targetedRelsInspection ?? optional($this->parent)->effectivePair();
  }

  /**
   * Targeted derivado del par (propio o heredado del padre).
   */
  public function getTargetedAttribute()
  {
    return optional($this->effectivePair())->targeted;
  }

  /**
   * InspectionType derivado del par (propio o heredado del padre).
   */
  public function getInspectionTypeAttribute()
  {
    return optional($this->effectivePair())->inspectionType;
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
