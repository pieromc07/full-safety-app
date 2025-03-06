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
    'description',
    'stock',
    'min_stock',
    'max_stock',
    'profit_margin',
    'price',
    'priceb',
    'pricec',
    'priced',
    'cost',
    'barcode',
    'image',
    'is_package',
    'unit_quantity',
    'product_id',
    'id_product_types',
    'id_categories',
    'id_brands',
    'id_units',
    'id_models',
    'id_enterprises',
    'uid_products',
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
    'price' => 'required',
    'cost' => 'required',
    'id_units' => 'required',
    'id_product_types' => 'required',
    'id_categories' => 'required',
    'id_enterprises' => 'required',
  ];

  /**
   * Error messages
   *
   * @var array<string, string>
   */
  public static $messages = [
    'name.required' => 'El nombre es obligatorio',
    'price.required' => 'El precio es obligatorio',
    'cost.required' => 'El costo es obligatorio',
    'id_units.required' => 'La unidad es obligatoria',
    'id_product_types.required' => 'El tipo de producto es obligatorio',
    'id_categories.required' => 'La categoría es obligatoria',
    'id_enterprises.required' => 'La empresa es obligatoria',
  ];

  /**
   * Tiemstamps
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the product that owns the product.
   */
  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }

  /**
   * Get the product type that owns the product.
   */
  public function productType()
  {
    return $this->belongsTo(ProductType::class, 'id_product_types');
  }

  /**
   * Get the category that owns the product.
   */
  public function category()
  {
    return $this->belongsTo(Category::class, 'id_categories');
  }

  /**
   * Get the brand that owns the product.
   */
  public function brand()
  {
    return $this->belongsTo(Brand::class, 'id_brands');
  }

  /**
   * Get the unit that owns the product.
   */
  public function unit()
  {
    return $this->belongsTo(Unit::class, 'id_units', 'id_units');
  }

  /**
   * Get the model that owns the product.
   */
  public function model()
  {
    return $this->belongsTo(Modelo::class, 'id_models');
  }

  /**
   * Get the enterprise that owns the product.
   */
  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_enterprises');
  }
}
