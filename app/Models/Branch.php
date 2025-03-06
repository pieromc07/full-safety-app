<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   * @var string
   */
  protected $table = 'branches';

  /**
   * The primary key associated with the table.
   * @var string
   */
  protected $primaryKey = 'id_branches';

  /**
   * The attributes that are mass assignable.
   * @var array<int, string>
   */

  protected $fillable = [
    'name',
    'address',
    'phone',
    'email',
    'is_main',
    'is_active',
    'igv',
    'id_enterprises',
    'id_districts',
  ];

  /**
   * Rules for validation
   */
  public static $rules = [
    'name' => 'required',
    'address' => 'required',
    'igv' => 'required',
    'id_enterprises' => 'required',
    'id_districts' => 'required',
  ];

  /**
   * Error messages
   */
  public static $messages = [
    'name.required' => 'El nombre es obligatorio',
    'address.required' => 'La dirección es obligatoria',
    'igv.required' => 'El IGV es obligatorio',
    'id_enterprises.required' => 'La empresa es obligatoria',
    'id_districts.required' => 'El distrito es obligatorio',
  ];

  /**
   * Tiemstamps
   */
  public $timestamps = false;

  /**
   * Get the enterprise that owns the branch.
   */
  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_enterprises', 'id_enterprises');
  }

  /**
   * Get the district that owns the branch.
   */
  public function district()
  {
    return $this->belongsTo(District::class, 'id_districts', 'id_districts');
  }

  /**
   * Get the province that owns the branch.
   */
  public function province()
  {
    $District = District::find($this->id_districts);
    return  Province::find($District->id_provinces);
  }

  /**
   * Get the departament that owns the branch.
   */
  public function departament()
  {
    $District = District::find($this->id_districts);
    $Province = Province::find($District->id_provinces);
    return Departament::find($Province->id_departaments);
  }
}
