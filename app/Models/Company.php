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
  protected $primaryKey = 'id_company';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'ruc',
    'name',
    'commercial_name',
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
}
