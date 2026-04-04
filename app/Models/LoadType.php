<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadType extends Model
{
  use HasFactory;

  protected $table = 'load_types';

  protected $primaryKey = 'id_load_types';

  protected $fillable = [
    'name',
    'description',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public function targeteds()
  {
    return $this->hasMany(Targeted::class, 'id_load_types');
  }
}
