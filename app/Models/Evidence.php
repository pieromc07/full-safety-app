<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evidence extends Model
{
  use HasFactory;

  protected $table = 'evidence';

  protected $fillable = [
    'name',
    'description',
    'category_id',
    'subcategory_id',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'description' => 'nullable',
    'category_id' => 'nullable|exists:categories,id',
    'subcategory_id' => 'required|exists:categories,id',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es requerido.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'description.nullable' => 'La descripción debe ser nula.',
    'category_id.nullable' => 'La categoría debe ser nula.',
    'category_id.exists' => 'La categoría no existe.',
    'subcategory_id.required' => 'La subcategoría es requerida.',
    'subcategory_id.exists' => 'La subcategoría no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function subcategory()
  {
    return $this->belongsTo(Category::class, 'subcategory_id');
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
