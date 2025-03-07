<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEnterprise extends Model
{
  use HasFactory;

  protected $table = 'product_enterprises';

  protected $primaryKey = 'id_product_enterprises';

  protected $fillable = [
    'id_products',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  public static $rules = [
    'id_products' => 'required|exists:products,id_products',
    'id_supplier_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_transport_enterprises' => 'required|exists:enterprises,id_enterprises',
  ];

  public static $messages = [
    'id_products.required' => 'El producto es obligatorio.',
    'id_products.exists' => 'El producto no existe.',
    'id_supplier_enterprises.required' => 'La empresa proveedora es obligatoria.',
    'id_supplier_enterprises.exists' => 'La empresa proveedora no existe.',
    'id_transport_enterprises.required' => 'La empresa de transporte es obligatoria.',
    'id_transport_enterprises.exists' => 'La empresa de transporte no existe.',
  ];

  public $timestamps = false;

  public function product()
  {
    return $this->belongsTo(Product::class, 'id_products', 'id_products');
  }

  public function supplierEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises', 'id_enterprises');
  }

  public function transportEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises', 'id_enterprises');
  }
}
