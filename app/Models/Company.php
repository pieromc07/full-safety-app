<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'companies';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_companies';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'ruc',
    'name',
    'commercial_name',
    'email',
    'phone',
    'address',
    'website',
    'representative',
    'logo',
    'cuid_inserted',
    'cuid_updated'
  ];

  /**
   * The timestamps associated with the table.
   *
   * @var boolean
   */
  public $timestamps = false;

  public static $rules = [
    'ruc' => 'required|string|size:11',
    'name' => 'required|max:128',
    'commercial_name' => 'nullable|max:128',
    'email' => 'nullable|email|max:128',
    'phone' => 'nullable|max:20',
    'address' => 'nullable|max:256',
    'website' => 'nullable|max:256',
    'representative' => 'nullable|max:128',
  ];

  public static $rulesMessages = [
    'ruc.required' => 'El RUC es obligatorio.',
    'ruc.size' => 'El RUC debe tener 11 caracteres.',
    'name.required' => 'La razón social es obligatoria.',
    'name.max' => 'La razón social no puede tener más de 128 caracteres.',
    'commercial_name.max' => 'El nombre comercial no puede tener más de 128 caracteres.',
    'email.email' => 'El email no es válido.',
    'email.max' => 'El email no puede tener más de 128 caracteres.',
    'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
    'address.max' => 'La dirección no puede tener más de 256 caracteres.',
    'website.max' => 'El sitio web no puede tener más de 256 caracteres.',
    'representative.max' => 'El representante no puede tener más de 128 caracteres.',
  ];
}
