<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'products';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_products';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'number_onu',
    'health',
    'flammability',
    'reactivity',
    'special',
    'uid_products',
    'id_product_types',
    'id_unit_types',
    'id_users_inserted',
    'id_users_updated',
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
   * Rules for validation
   *
   * @var array<string, string>
   */
  public static $rules = [
    'name' => 'required',
    'number_onu' => 'required',
    'health' => 'required',
    'flammability' => 'required',
    'reactivity' => 'required',
    'special' => 'required',
    'id_product_types' => 'required',
    'id_unit_types' => 'required',
  ];

  /**
   * Error messages
   *
   * @var array<string, string>
   */
  public static $messages = [
    'name.required' => 'El nombre es requerido',
    'number_onu.required' => 'El número ONU es requerido',
    'health.required' => 'La salud es requerida',
    'flammability.required' => 'La inflamabilidad es requerida',
    'reactivity.required' => 'La reactividad es requerida',
    'special.required' => 'La especialidad es requerida',
    'id_product_types.required' => 'El tipo de producto es requerido',
    'id_unit_types.required' => 'La unidad es requerida',

  ];

  /**
   * Tiemstamps
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the product type that owns the product.
   */
  public function productType()
  {
    return $this->belongsTo(ProductType::class, 'id_product_types', 'id_product_types');
  }

  /**
   * Get the unit type that owns the product.
   */
  public function unitType()
  {
    return $this->belongsTo(UnitType::class, 'id_unit_types', 'id_unit_types');
  }

  /**
   * Get the user that inserted the product.
   */
  public function userInserted()
  {
    return $this->belongsTo(User::class, 'id_users_inserted', 'id_users');
  }

  /**
   * Get the user that updated the product.
   */
  public function userUpdated()
  {
    return $this->belongsTo(User::class, 'id_users_updated', 'id_users');
  }
}
