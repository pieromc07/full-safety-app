<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use HasFactory, Notifiable, HasRoles;

  protected $table = 'users';

  protected $fillable = [
    'name',
    'username',
    'password',
    'is_active',
    'token',
    'cuid_inserted',
    'cuid_updated',
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'username' => 'required|max:16|unique:users,username',
    'password' => 'required|max:256',
  ];

  public static $rulesLogin = [
    'username' => 'required|max:16',
    'password' => 'required|max:256',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'username.required' => 'El nombre de usuario es obligatorio.',
    'username.max' => 'El nombre de usuario no puede tener más de 16 caracteres.',
    'username.unique' => 'El nombre de usuario ya está en uso.',
    'password.required' => 'La contraseña es obligatoria.',
    'password.max' => 'La contraseña no puede tener más de 256 caracteres.',
  ];

  public static $rulesMessagesLogin = [
    'username.required' => 'El nombre de usuario es obligatorio.',
    'username.max' => 'El nombre de usuario no puede tener más de 16 caracteres.',
    'password.required' => 'La contraseña es obligatoria.',
    'password.max' => 'La contraseña no puede tener más de 256 caracteres.',
  ];

  protected $hidden = [
    'password',
    'token',
    'cuid_inserted',
    'cuid_updated',
  ];

  public $timestamps = false;

  protected $casts = [];

  public function cuidInsertedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_inserted);
  }

  public function cuidUpdatedToDatetime()
  {
    return $this->cuidToDatetime($this->cuid_updated);
  }

  public function cuidToDatetime($cuid)
  {
    return DB::selectOne('SELECT CUID_TO_DATETIME(?) AS datetime', [$cuid])->datetime;
  }

  public function isActive()
  {
    return $this->is_active;
  }

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }
}
