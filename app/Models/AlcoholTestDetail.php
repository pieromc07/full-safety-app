<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlcoholTestDetail extends Model
{
  use HasFactory;

  protected $table = 'alcohol_test_details';

  protected $primaryKey = 'id_alcohol_test_details';

  protected $fillable = [
    'id_alcohol_tests',
    'id_employees',
    'result',
    'state',
    'photo_one',
    'photo_one_uri',
    'photo_two',
    'photo_two_uri',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  protected $hidden = ['cuid_inserted', 'cuid_updated', 'cuid_deleted'];

  public $timestamps = false;

  public static $rules = [
    'id_alcohol_tests' => 'required|exists:alcohol_tests,id_alcohol_tests',
    'id_employees' => 'required|exists:employees,id_employees',
    'result' => 'nullable|numeric',
    'state' => 'nullable|integer',
    'photo_one' => 'nullable|string',
    'photo_one_uri' => 'nullable|string',
    'photo_two' => 'nullable|string',
    'photo_two_uri' => 'nullable|string',
  ];

  public static $messages = [
    'id_alcohol_tests.required' => 'La prueba de alcohol es obligatoria.',
    'id_alcohol_tests.exists' => 'La prueba de alcohol seleccionada no es válida.',

    'id_employees.required' => 'El empleado es obligatorio.',
    'id_employees.exists' => 'El empleado seleccionado no es válido.',

    'result.numeric' => 'El resultado debe ser un número.',

    'state.integer' => 'El estado debe ser un número entero.',

    'photo_one.string' => 'La foto uno debe ser una cadena de texto.',
    'photo_one_uri.string' => 'La URI de la foto uno debe ser una cadena de texto.',

    'photo_two.string' => 'La foto dos debe ser una cadena de texto.',
    'photo_two_uri.string' => 'La URI de la foto dos debe ser una cadena de texto.',
  ];

  public function alcoholTest()
  {
    return $this->belongsTo(AlcoholTest::class, 'id_alcohol_tests', 'id_alcohol_tests');
  }

  public function employee()
  {
    return $this->belongsTo(Employee::class, 'id_employees', 'id_employees');
  }
}
