<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMovementDetail extends Model
{
  use HasFactory;

  protected $table = 'unit_movement_details';
  protected $primaryKey = 'id_unit_movement_details';

  protected $fillable = [
    'id_unit_movements',
    'weight',
    'id_units',
    'id_products_two',
    'weight_two',
    'referral_guide',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'id_unit_movements' => 'required|integer|exists:unit_movements,id_unit_movements',
    'weight' => 'required|numeric',
    'id_units' => 'required|integer|exists:units,id_units',
    'id_products_two' => 'nullable|integer|exists:products,id_products',
    'weight_two' => 'nullable|numeric',
    'referral_guide' => 'nullable|string|max:50',
  ];

  public static $messages = [
    'id_unit_movements.required' => 'El campo movimiento de unidad es obligatorio.',
    'id_unit_movements.integer' => 'El campo movimiento de unidad debe ser un número entero.',
    'id_unit_movements.exists' => 'El movimiento de unidad seleccionado no existe.',
    'weight.required' => 'El campo peso es obligatorio.',
    'weight.numeric' => 'El campo peso debe ser un número.',
    'id_units.required' => 'El campo unidad es obligatorio.',
    'id_units.integer' => 'El campo unidad debe ser un número entero.',
    'id_units.exists' => 'La unidad seleccionada no existe.',
    'id_products_two.integer' => 'El campo producto dos debe ser un número entero.',
    'id_products_two.exists' => 'El producto dos seleccionado no existe.',
    'weight_two.numeric' => 'El campo peso dos debe ser un número.',
    'referral_guide.string' => 'El campo guía de remisión debe ser una cadena de texto.',
    'referral_guide.max' => 'El campo guía de remisión no debe ser mayor a 50 caracteres.',
  ];


  public $timestamps = false;

  public function unitMovement()
  {
    return $this->belongsTo(UnitMovement::class, 'id_unit_movements', 'id_unit_movements');
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class, 'id_units', 'id_units');
  }

  public function productTwo()
  {
    return $this->belongsTo(Product::class, 'id_products_two', 'id_products');
  }
}
