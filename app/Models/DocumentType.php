<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_types';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id_document_types';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'code',
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  /**
   * hidden
   */
  protected $hidden = [
    'id_users_inserted',
    'id_users_updated',
    'cuid_inserted',
    'cuid_updated',
    'cuid_deleted'
  ];

  /**
   * Rules
   */
  public static $rules = [
    'name' => 'required',
    'id_users_inserted' => 'nullable|integer',
    'id_users_updated' => 'nullable|integer',
    'cuid_inserted' => 'nullable|integer',
    'cuid_updated' => 'nullable|integer',
    'cuid_deleted' => 'nullable|integer'
  ];

  public static $messages = [
    'name.required' => 'El nombre del tipo de documento es requerido',
    'id_users_inserted.integer' => 'El id del usuario que insertó el registro es requerido',
    'id_users_updated.integer' => 'El id del usuario que actualizó el registro es requerido',
    'cuid_inserted.integer' => 'El cuid del usuario que insertó el registro es requerido',
    'cuid_updated.integer' => 'El cuid del usuario que actualizó el registro es requerido',
    'cuid_deleted.integer' => 'El cuid del usuario que eliminó el registro es requerido'
  ];

  /**
   * Tiemstamps
   */

  public $timestamps = false;

  /**
   * Get the enterprise that owns the document type.
   */
  public function enterprise()
  {
    return $this->belongsTo(Enterprise::class, 'id_enterprises');
  }
}
