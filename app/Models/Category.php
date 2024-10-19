<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
  use HasFactory;

  protected $table = 'categories';

  protected $fillable = [
    'name',
    'parent_id',
    'targeted_id',
    'inspection_type_id',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'name' => 'required|max:64',
    'parent_id' => 'nullable|exists:categories,id',
    'targeted_id' => 'nullable|exists:targeteds,id',
    'inspection_type_id' => 'nullable|exists:inspection_types,id',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 64 caracteres.',
    'parent_id.exists' => 'La categoría padre no existe.',
    'targeted_id.exists' => 'El público objetivo no existe.',
    'inspection_type_id.exists' => 'El tipo de inspección no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  public function parent()
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'targeted_id');
  }

  public function inspectionType()
  {
    return $this->belongsTo(InspectionType::class, 'inspection_type_id');
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
