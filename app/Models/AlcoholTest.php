<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AlcoholTest extends Model
{
  use HasFactory;

  protected $table = 'alcohol_tests';

  protected $primaryKey = 'id_alcohol_tests';

  protected $fillable = [
    'date',
    'hour',
    'id_checkpoints',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'id_users',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated', 'cuid_deleted'];

  public $timestamps = false;

  public static $rules = [
    'date' => 'nullable|date',
    'hour' => 'nullable|date_format:H:i:s',
    'id_supplier_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_transport_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_checkpoints' => 'required|exists:checkpoints,id_checkpoints',
  ];

  public static $messages = [
    'date.date' => 'La fecha debe ser válida.',
    'hour.date_format' => 'La hora debe tener el formato HH:MM:SS.',

    'id_supplier_enterprises.required' => 'El proveedor es obligatorio.',
    'id_supplier_enterprises.exists' => 'El proveedor seleccionado no es válido.',

    'id_transport_enterprises.required' => 'La empresa de transporte es obligatoria.',
    'id_transport_enterprises.exists' => 'La empresa de transporte seleccionada no es válida.',

    'id_checkpoints.required' => 'El punto de control es obligatorio.',
    'id_checkpoints.exists' => 'El punto de control seleccionado no es válido.',

  ];

  public function checkpoint()
  {
    return $this->belongsTo(CheckPoint::class, 'id_checkpoints', 'id_checkpoints');
  }

  public function enterpriseSupplier()
  {
    return $this->belongsTo(Enterprise::class, 'id_supplier_enterprises', 'id_enterprises');
  }

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises', 'id_enterprises');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id_users', 'id_users');
  }

  public function details()
  {
    return $this->hasMany(AlcoholTestDetail::class, 'id_alcohol_tests', 'id_alcohol_tests');
  }

  public function cuidInsertedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_inserted);
  }

  public function cuidUpdatedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_updated);
  }

  private function cuidToDatetime($cuid)
  {
    $result = DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid]);
    return $result ? $result->datetime : null;
  }
}
