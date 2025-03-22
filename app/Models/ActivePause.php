<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivePause extends Model
{
  use HasFactory;

  protected $table = 'active_pauses';

  protected $primaryKey = 'id_active_pauses';

  protected $fillable = [
    'date',
    'hour',
    'id_checkpoints',
    'id_supplier_enterprises',
    'id_transport_enterprises',
    'participants',
    'photo_one',
    'photo_two',
    'id_users',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated'];

  public $timestamps = false;

  public static $rules = [
    'date' => 'nullable|date',
    'hour' => 'nullable|date_format:H:i:s',
    'id_supplier_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_transport_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_checkpoints' => 'required|exists:checkpoints,id_checkpoints',
    'participants' => 'required|integer|min:1',
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

    'participants.required' => 'El número de participantes es obligatorio.',
    'participants.integer' => 'El número de participantes debe ser un número entero.',
    'participants.min' => 'Debe haber al menos 1 participante.',
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
