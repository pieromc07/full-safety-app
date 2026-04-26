<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evidence extends Model
{
  use HasFactory;

  protected $table = 'evidences';

  protected $primaryKey = 'id_evidences';

  protected $fillable = [
    'name',
    'description',
    'id_subcategories',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'description' => 'nullable',
    'id_subcategories' => 'required|exists:categories,id_categories',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es requerido.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'description.nullable' => 'La descripción debe ser nula.',
    'id_subcategories.required' => 'La subcategoría es requerida.',
    'id_subcategories.exists' => 'La subcategoría no existe.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public function subcategory()
  {
    return $this->belongsTo(Category::class, 'id_subcategories', 'id_categories');
  }

  /**
   * Categoría padre derivada de la subcategoría (no es una columna en DB).
   */
  public function category()
  {
    return $this->subcategory ? $this->subcategory->parent : null;
  }

  public function categoryName()
  {
    return optional($this->category())->name;
  }

  public function subcategoryName()
  {
    return optional($this->subcategory)->name;
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
