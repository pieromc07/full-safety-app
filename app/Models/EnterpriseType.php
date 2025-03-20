<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnterpriseType extends Model
{
  use HasFactory;

  protected $table = 'enterprise_types';

  protected $primaryKey = 'id_enterprise_types';

  protected $fillable = [
    'name',
    'description',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public static $rules = [
    'name' => 'required|max:32',
    'description' => 'nullable',
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 32 caracteres.',
  ];

  protected $hidden = [
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted',
  ];

  public $timestamps = false;

  public function enterprises()
  {
    return $this->hasMany(Enterprise::class, 'id_enterprise_types');
  }

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
}
