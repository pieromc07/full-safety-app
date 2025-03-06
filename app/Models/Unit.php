<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   * @var string
   */
  protected $table = 'units';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_units';

  /**
   * The attributes that are mass assignable.
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'abbreviation',
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
   * Rules for validation
   */
  public static $rules = [
    'name' => 'required',
    'abbreviation' => 'required',
  ];

  /**
   * Error messages
   */
  public static $messages = [
    'name.required' => 'El nombre es obligatorio',
    'abbreviation.required' => 'La empresa es obligatoria',
  ];

  /**
   * Tiemstamps
   */
  public $timestamps = false;

  /**
   * Get the products for the unit.
   */
  public function products()
  {
    return $this->hasMany(Product::class);
  }
}
