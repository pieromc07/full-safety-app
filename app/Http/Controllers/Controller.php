<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
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
}
