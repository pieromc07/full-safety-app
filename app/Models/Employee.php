<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = [
    'document',
    'name',
    'lastname',
    'fullname',
    'transport_enterprise_id',
    'cuid_inserted',
    'cuid_updated'
  ];

  // Reglas de validación
  public static $rules = [
    'document' => 'required|string|max:16',
    'name' => 'required|string|max:50',
    'lastname' => 'required|string|max:50',
    'transport_enterprise_id' => 'required|exists:enterprises,id',
  ];

  // Mensajes de validación personalizados
  public static $rulesMessages = [
    'document.required' => 'El documento es obligatorio.',
    'document.string' => 'El documento debe ser una cadena de texto.',
    'document.max' => 'El documento no debe exceder los 16 caracteres.',
    'document.unique' => 'El documento ya está registrado en el sistema.',
    'name.required' => 'El nombre es obligatorio.',
    'name.string' => 'El nombre debe ser una cadena de texto.',
    'name.max' => 'El nombre no debe exceder los 50 caracteres.',
    'lastname.required' => 'El apellido es obligatorio.',
    'lastname.string' => 'El apellido debe ser una cadena de texto.',
    'lastname.max' => 'El apellido no debe exceder los 50 caracteres.',
    'transport_enterprise_id.required' => 'La empresa de transporte es obligatoria.',
    'transport_enterprise_id.exists' => 'La empresa de transporte seleccionada no es válida.',
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated'];

  public $timestamps = false;

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'transport_enterprise_id');
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
