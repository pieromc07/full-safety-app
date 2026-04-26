<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
  use HasFactory;

  protected $table = 'employees';

  protected $primaryKey = 'id_employees';

  protected $fillable = [
    'document',
    'name',
    'lastname',
    'fullname',
    'id_transport_enterprises',
    'id_users',
    'id_targeteds',
    'job_title',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  // Reglas base. La regla condicional `required_if` para job_title se aplica
  // en EmployeeController porque depende del id_targeteds runtime de "Otro".
  public static $rules = [
    'document' => 'required|string|max:16',
    'name' => 'required|string|max:50',
    'lastname' => 'required|string|max:50',
    'id_transport_enterprises' => 'required|exists:enterprises,id_enterprises',
    'id_targeteds' => 'nullable|exists:targeteds,id_targeteds',
    'job_title' => 'nullable|string|max:128',
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
    'id_transport_enterprises.required' => 'La empresa de transporte es obligatoria.',
    'id_transport_enterprises.exists' => 'La empresa de transporte seleccionada no es válida.',
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated', 'cuid_deleted'];

  public $timestamps = false;

  public function enterpriseTransport()
  {
    return $this->belongsTo(Enterprise::class, 'id_transport_enterprises');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id_users', 'id_users');
  }

  /**
   * Dirigido (rol/puesto). Apunta a un hijo de la raíz "Persona".
   */
  public function targeted()
  {
    return $this->belongsTo(Targeted::class, 'id_targeteds', 'id_targeteds');
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
