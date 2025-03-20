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
    'id_targeteds',
    'id_inspection_types',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:64',
    'parent_id' => 'nullable|exists:categories,id_categories',
    'id_targeteds' => 'nullable|exists:targeteds,id_targeteds',
    'id_inspection_types' => 'nullable|exists:inspection_types,id_inspection_types',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 64 caracteres.',
    'parent_id.exists' => 'La categoría padre no existe.',
    'id_targeteds.exists' => 'El público objetivo no existe.',
    'id_inspection_types.exists' => 'El tipo de inspección no existe.',
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
