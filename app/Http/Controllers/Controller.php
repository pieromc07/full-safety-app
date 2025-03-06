<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  public static function saveImage($image, $folder)
  {
    try {
      return Storage::disk('public')->put($folder, $image);
    } catch (\Exception $e) {
      return null;
    }
  }

  public static function dropImage($path)
  {
    try {
      return Storage::disk('public')->delete($path);
    } catch (\Exception $e) {
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

  /**
   * Access to the master role
   */
  public function AccessMaster()
  {
    return session('role') === 'master';
  }

  /**
   * replace or verify the enterprise if it is different from the session or the role is master
   */
  public function replaceOrVerifyEnterprise()
  {
    if (!Request::has('id_enterprises')) {
      Request::merge(['id_enterprises' => Request::get('enterpriseId')]);
    }
  }

  /**
   * saving files to storage
   */
  public function saveFiles($file, $path, $name = null)
  {
    if ($name) {
      $name = $name . '.' . $file->getClientOriginalExtension();
      $file->move(public_path($path), $name);
      return $path . '/' . $name;
    }
    $name = time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path($path), $name);
    return $path . '/' . $name;
  }

  /**
   * delete files from storage
   */
  public function deleteFiles($path)
  {
    $fullPath = public_path($path);
    if (file_exists($fullPath)) {
      try {
        unlink($fullPath);
      } catch (\Exception $e) {
        // Manejar la excepción, por ejemplo, registrarla en el log
        Log::error('Error deleting file: ' . $e->getMessage());
      }
    }
  }
}
