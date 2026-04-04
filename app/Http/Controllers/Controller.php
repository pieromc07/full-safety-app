<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  public static function saveImage($image, $folder)
  {
    try {
      // Verifica si el archivo es válido
      if (!$image->isValid()) {
        return null;
      }

      // Genera un nombre único para la imagen
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

      // Define la ruta completa donde se guardará la imagen
      $path =  'uploads/' . $folder;
      $fullPath = public_path($path);

      // Crea la carpeta si no existe
      if (!file_exists($fullPath)) {
        mkdir($fullPath, 0755, true);
      }

      // Mueve la imagen a la carpeta especificada
      $image->move($fullPath, $fileName);

      // Retorna la ruta relativa de la imagen guardada
      return $path . '/' . $fileName;
    } catch (\Exception $e) {
      // Manejo de errores
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
   * Encrypt text using Laravel's built-in encryption (AES-256-CBC).
   * @param string $text
   * @return string
   */
  public static function encryptText($text)
  {
    return encrypt($text);
  }

  /**
   * Decrypt text using Laravel's built-in encryption (AES-256-CBC).
   * @param string $text
   * @return string
   */
  public static function decryptText($text)
  {
    return decrypt($text);
  }
}
