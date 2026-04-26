<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  /**
   * Soft delete usando CUID - marca cuid_deleted en vez de eliminar fisicamente.
   */
  public static function softDelete(Model $model): void
  {
    $table = $model->getTable();
    $pk = $model->getKeyName();
    DB::table($table)
      ->where($pk, $model->getKey())
      ->update(['cuid_deleted' => DB::raw('CUID_19D_B10()')]);
  }

  /**
   * Soft delete por query (para eliminación en cascada por FK).
   */
  public static function softDeleteWhere(string $table, string $column, $value): void
  {
    DB::table($table)
      ->where($column, $value)
      ->whereNull('cuid_deleted')
      ->update(['cuid_deleted' => DB::raw('CUID_19D_B10()')]);
  }

  public static function saveImage($image, $folder)
  {
    static $allowedMimes = [
      'image/jpeg' => 'jpg',
      'image/png'  => 'png',
      'image/webp' => 'webp',
      'image/gif'  => 'gif',
    ];
    static $allowedFolders = ['targeteds', 'companies', 'enterprises', 'employees', 'products'];

    try {
      if (!$image || !$image->isValid()) {
        return null;
      }

      if (!in_array($folder, $allowedFolders, true)) {
        return null;
      }

      $mime = $image->getMimeType();
      if (!isset($allowedMimes[$mime])) {
        return null;
      }

      $info = @getimagesizefromstring(file_get_contents($image->getRealPath()));
      if ($info === false) {
        return null;
      }

      $extension = $allowedMimes[$mime];
      $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

      $path = 'uploads/' . $folder;
      $fullPath = public_path($path);

      if (!file_exists($fullPath)) {
        mkdir($fullPath, 0755, true);
      }

      $image->move($fullPath, $fileName);

      return $path . '/' . $fileName;
    } catch (\Exception $e) {
      return null;
    }
  }

  public static function dropImage($path)
  {
    try {
      // Obtiene la ruta completa del archivo
      $fullPath = public_path($path);

      // Verifica si el archivo existe
      if (file_exists($fullPath)) {
        // Elimina el archivo
        unlink($fullPath);
        return true;
      }

      // Si no existe, retorna false
      return false;
    } catch (\Exception $e) {
      // Manejo de errores
      return false;
    }
  }
  /**
   * Global configuration of the controller
   */
  public const TAKE = 5;

  public const MEDIUMTAKE = 10;

  public const LARGETAKE = 20;

  public const BIGTAKE = 50;

  public const HUGETAKE = 100;
}
