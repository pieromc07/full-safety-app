<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use HasApiTokens, HasFactory, Notifiable, HasRoles;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'fullname',
    'username',
    'password',
    'token',
    'status',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'password' => 'hashed',
  ];

  /**
   * The tiemstamps.
   *
   * @var bool
   */
  public $timestamps = false;

  /*
    * Rules
   */
  public static $rules = [
    'username' => 'required|string|max:255',
    'password' => 'required|string|min:8|max:255',
  ];

  public static $rulesLogin = [
    'username' => 'required|string|max:255',
    'password' => 'required|string|min:8|max:255',
  ];
  /*
    * Messages
   */
  public static $messages = [
    'username.required' => 'El nombre de usuario es requerido',
    'username.string' => 'El nombre de usuario debe ser una cadena de caracteres',
    'username.max' => 'El nombre de usuario no debe exceder los 255 caracteres',
    'password.required' => 'La contraseña es requerida',
    'password.string' => 'La contraseña debe ser una cadena de caracteres',
    'password.min' => 'La contraseña debe tener al menos 8 caracteres',
    'password.max' => 'La contraseña no debe exceder los 255 caracteres',
  ];

  public static $messagesLogin = [
    'username.required' => 'El nombre de usuario es requerido',
    'username.string' => 'El nombre de usuario debe ser una cadena de caracteres',
    'username.max' => 'El nombre de usuario no debe exceder los 255 caracteres',
    'password.required' => 'La contraseña es requerida',
    'password.string' => 'La contraseña debe ser una cadena de caracteres',
  ];

  /*
    * Validation
  */

  /**
   * username and id_enterprises must be unique
   *
   * @param  string  $username
   * @param  int  $id_enterprises
   * @return bool
   */
  public static function isUnique($username, $id_enterprises)
  {
    return !User::where('username', $username)->where('id_enterprises', $id_enterprises)->exists();
  }


  /*
    * Relationships
   */

  /**
   * Get the enterprise that owns the user.
   */

  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_enterprises');
  }

  /**
   * Get the branch that owns the user.
   */
  public function branch()
  {
    return $this->belongsTo(Branch::class, 'id_branches');
  }

  /**
   * Get the employee that owns the user.
   */
  public function employee()
  {
    return $this->hasOne(Employee::class, 'id_users', 'id_users');
  }

  /*
    * Mutators
   */

  /**
   * Disable the user instance.
   */
  public function disable()
  {
    $this->cuid_deleted = DB::raw('CUID_19D_B10()');
    $this->save();
  }

  /**
   * Enable the user instance.
   */
  public function enable()
  {
    $this->cuid_deleted = null;
    $this->save();
  }

  /**
   * Check if the user is active.
   *
   * @return bool
   */
  public function isActive()
  {
    return DB::table('users')->where('id_users', $this->id_users)->whereNull('cuid_deleted')->exists();
  }

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }
}
