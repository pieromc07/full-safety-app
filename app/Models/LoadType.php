<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadType extends Model
{
  use HasFactory;

  protected $table = 'load_types';

  protected $primaryKey = 'id_load_types';

  protected $fillable = [
    'name',
    'description',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public static $rules = [
    'name' => 'required|max:128',
    'description' => 'max:256',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'description.max' => 'La descripción no puede tener más de 256 caracteres.',
  ];

  public function targetedRelsLoadTypes()
  {
    return $this->hasMany(TargetedRelsLoadType::class, 'id_load_types', 'id_load_types');
  }
}
