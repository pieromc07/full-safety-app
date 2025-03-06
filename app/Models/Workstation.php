<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
  use HasFactory;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'workstations';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_workstations';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'id_enterprises',
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  /**
   * hidden
   * @var array<int, string>
   */
  protected $hidden = [
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  /**
   * Tiemstamps
   */
  public $timestamps = false;

  /**
   * Rules
   */
  public static $rules = [
    'name' => 'required',
    'id_enterprises' => 'nullable|integer'
  ];

  public static $messages = [
    'name.required' => 'El nombre de la estación de trabajo es requerido',
    'id_enterprises.integer' => 'El id de la empresa es requerido'
  ];

  /**
   * Get the enterprise that owns the workstation.
   */
  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_enterprises');
  }

  /**
   * Get the employees for the workstation.
   */
  public function employees()
  {
    return $this->hasMany(Employee::class, 'id_workstations');
  }
}
