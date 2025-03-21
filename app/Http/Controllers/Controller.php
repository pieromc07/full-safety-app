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

  /**
   * Algorithm to encrypt the text
   * @param string $text
   * @return string
   */
  public static function encryptText($text)
  {
    // Cifrado César
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    $numeros = '0123456789';
    $special = '!@#$%^&*()_+{}|:"<>?';
    $alphabet .= $numeros;
    $alphabet .= $special;
    $text = strtolower($text);
    $text = str_replace(' ', '', $text);
    $shift = 3;
    $encrypted = '';
    for ($i = 0; $i < strlen($text); $i++) {
      $pos = strpos($alphabet, $text[$i]);
      if ($pos !== false) {
        $newPos = ($pos + $shift) % strlen($alphabet);
        $encrypted .= $alphabet[$newPos];
      } else {
        $encrypted .= $text[$i];
      }
    }
    return $encrypted;
  }

  /**
   * Algorithm to decrypt the text
   * @param string $text
   * @return string
   */
  public static function decryptText($text)
  {
    // Descifrado César
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    $numeros = '0123456789';
    $special = '!@#$%^&*()_+{}|:"<>?';
    $alphabet .= $numeros;
    $alphabet .= $special;
    $text = strtolower($text);
    $text = str_replace(' ', '', $text);
    $shift = 3;
    $decrypted = '';
    for ($i = 0; $i < strlen($text); $i++) {
      $pos = strpos($alphabet, $text[$i]);
      if ($pos !== false) {
        $newPos = ($pos - $shift) % strlen($alphabet);
        if ($newPos < 0) {
          $newPos = strlen($alphabet) + $newPos;
        }
        $decrypted .= $alphabet[$newPos];
      } else {
        $decrypted .= $text[$i];
      }
    }
    return $decrypted;
  }
}
