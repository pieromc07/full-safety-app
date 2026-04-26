<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   * @var string
   */
  protected $table = 'unit_types';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_unit_types';

  /**
   * The attributes that are mass assignable.
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'image',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * The attributes that should be hidden for arrays.
   * @var array<int, string>
   */
  protected $hidden = [
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * Timestamps
   * @var bool
   */
  public $timestamps = false;

  /**
   * Rules for validation
   */
  public static $rules = [
    'name' => 'required|max:128',
  ];

  /**
   * Error messages
   */
  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
  ];

  public function products()
  {
    return $this->hasMany(Product::class, 'id_unit_types');
  }
}
