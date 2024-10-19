<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InspectionType extends Model
{
  use HasFactory;

  protected $table = 'inspection_types';

  protected $fillable = [
    'name',
    'description',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'name' => 'required|max:32',
    'description' => 'nullable',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 32 caracteres.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  // public function inspections()
  // {
  //   return $this->hasMany(Inspection::class, 'inspection_type_id');
  // }

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
