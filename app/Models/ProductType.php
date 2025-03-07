<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'product_types';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_product_types';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'code',
    'name',
    'parent_id',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
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
   * The attributes that should be cast to native types.
   * @var array<int, string>
   */
  protected $casts = [
    'id_product_types' => 'integer',
    'code' => 'string',
    'name' => 'string',
    'parent_id' => 'integer',
    'id_users_inserted' => 'integer',
    'id_users_updated' => 'integer',
    'cuid_inserted' => 'integer',
    'cuid_updated' => 'integer',
    'cuid_deleted' => 'integer',
  ];

  /**
   * Rules for validation
   */
  public static $rules = [
    'code' => 'required',
    'name' => 'required',
  ];

  /**
   * Error messages
   */

  public static $messages = [
    'codigo.required' => 'El codigo es obligatorio',
    'name.required' => 'El nombre es obligatorio',
  ];

  /**
   * Tiemstamps
   */
  public $timestamps = false;


  /**
   * Get the products for the product type.
   */
  public function products()
  {
    return $this->hasMany(Product::class, 'id_product_types', 'id_product_types');
  }

  /**
   * Get the parent product type.
   */
  public function parent()
  {
    return $this->belongsTo(ProductType::class, 'parent_id', 'id_product_types');
  }
}
