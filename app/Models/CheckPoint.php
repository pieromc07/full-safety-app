<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckPoint extends Model
{
  use HasFactory;

  protected $table = 'checkpoints';

  protected $primaryKey = 'id_checkpoints';

  protected $fillable = [
    'name',
    'description',
    'cuid_inserted',
    'cuid_updated'
  ];

  public static $rules = [
    'name' => 'required|max:128',
    'description' => 'max:256'
  ];

  public static $rulesMessages = [
    'name.required' => 'El nombre es obligatorio.',
    'name.max' => 'El nombre no puede tener más de 128 caracteres.',
    'description.max' => 'La descripción no puede tener más de 256 caracteres.'
  ];

  public $timestamps = false;

  public function inspections()
  {
    return $this->hasMany(Inspection::class, 'checkpoint_id');
  }
}
