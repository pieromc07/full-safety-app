<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CheckPoint;

class UnitMovement extends Model
{
  use HasFactory;

  protected $table = 'unit_movements';
  protected $primaryKey = 'id_unit_movements';
  protected $fillable = [
    'date',
    'time',
    'time_arrival',
    'time_departure',
    'id_checkpoints',
    'convoy',
    'convoy_state',
    'heavy_vehicle',
    'light_vehicle',
    'direction',
    'id_users',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'id_products',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'date' => 'required|date',
    'id_checkpoints' => 'required|integer|exists:checkpoints,id_checkpoints',
    'convoy' => 'required|integer',
    'convoy_state' => 'required|integer',
    'heavy_vehicle' => 'required|integer',
    'light_vehicle' => 'required|integer',
    'direction' => 'required|integer',
    'id_supplier_enterprises' => 'required|integer|exists:enterprises,id_enterprises',
    'id_transport_enterprises' => 'required|integer|exists:enterprises,id_enterprises',
    'id_products' => 'required|integer|exists:products,id_products',
  ];

  public static $messages = [
    'date.required' => 'El campo fecha es obligatorio.',
    'date.date' => 'El campo fecha debe ser una fecha válida.',
    'id_checkpoints.required' => 'El campo puesto de control es obligatorio.',
    'id_checkpoints.integer' => 'El campo puesto de control debe ser un número entero.',
    'id_checkpoints.exists' => 'El puesto de control seleccionado no existe.',
    'convoy.required' => 'El campo convoy es obligatorio.',
    'convoy.integer' => 'El campo convoy debe ser un número entero.',
    'convoy_state.required' => 'El campo estado de convoy es obligatorio.',
    'convoy_state.integer' => 'El campo estado de convoy debe ser un número entero.',
    'heavy_vehicle.required' => 'El campo vehículo pesado es obligatorio.',
    'heavy_vehicle.integer' => 'El campo vehículo pesado debe ser un número entero.',
    'light_vehicle.required' => 'El campo vehículo liviano es obligatorio.',
    'light_vehicle.integer' => 'El campo vehículo liviano debe ser un número entero.',
    'direction.required' => 'El campo dirección es obligatorio.',
    'direction.integer' => 'El campo dirección debe ser un número entero.',
    'id_supplier_enterprises.required' => 'El campo empresa proveedora es obligatorio.',
    'id_supplier_enterprises.integer' => 'El campo empresa proveedora debe ser un número entero.',
    'id_supplier_enterprises.exists' => 'La empresa proveedora seleccionada no existe.',
    'id_transport_enterprises.required' => 'El campo empresa transportista es obligatorio.',
    'id_transport_enterprises.integer' => 'El campo empresa transportista debe ser un número entero.',
    'id_transport_enterprises.exists' => 'La empresa transportista seleccionada no existe.',
    'id_products.required' => 'El campo empresa productora es obligatorio.',
    'id_products.integer' => 'El campo empresa productora debe ser un número entero.',
    'id_products.exists' => 'La empresa productora seleccionada no existe.',
  ];

  public $timestamps = false;

  public function unitMovementDetails()
  {
    return $this->hasMany(UnitMovementDetail::class, 'id_unit_movements', 'id_unit_movements');
  }

  public function checkpoint()
  {
    return $this->belongsTo(Checkpoint::class, 'id_checkpoints', 'id_checkpoints');
  }

  public function supplierEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises', 'id_enterprises');
  }

  public function transportEnterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises', 'id_enterprises');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'id_products', 'id_products');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id_users', 'id_users');
  }

  public function nroConvoy()
  {
    if ($this->convoy == 1) {
      return 'Primero';
    } elseif ($this->convoy == 2) {
      return 'Segundo';
    } elseif ($this->convoy == 3) {
      return 'Tercero';
    } elseif ($this->convoy == 4) {
      return 'Cuarto';
    } elseif ($this->convoy == 5) {
      return 'Quinto';
    } elseif ($this->convoy == 6) {
      return 'Sexto';
    } elseif ($this->convoy == 7) {
      return 'Séptimo';
    } elseif ($this->convoy == 8) {
      return 'Octavo';
    } elseif ($this->convoy == 9) {
      return 'Noveno';
    } elseif ($this->convoy == 10) {
      return 'Décimo';
    }
  }
}
