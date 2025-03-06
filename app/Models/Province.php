<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'provinces';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_provinces';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

  protected $fillable = [
    'name',
    'ubigeo',
    'id_departaments',
  ];

  /**
   * Tiemstamps
   */
  public $timestamps = false;

  /**
   * Get the departament that owns the province.
   */
  public function departament()
  {
    return $this->belongsTo(Departament::class, 'id_departaments', 'id_departaments');
  }

  /**
   * Get the districts for the province.
   */
  public function districts()
  {
    return $this->hasMany(District::class, 'id_provinces', 'id_provinces');
  }
}
