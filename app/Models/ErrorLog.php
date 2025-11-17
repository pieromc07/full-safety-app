<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'error_logs';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_error_logs';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'date',
    'type',
    'source',
    'message',
    'trace',
    'data',
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];


  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array<int, string>
   */
  protected $casts = [
    'date' => 'datetime',
    'data' => 'array',
  ];


  /**   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}
