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
