<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct() {}

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login()
  {
    $credentials = request(['username', 'password']);

    if (!$token = JWTAuth::attempt($credentials)) {
      return response()->json(['status' => false, 'message' => 'Datos incorrectos'], 401);
    }
    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(JWTAuth::user());
  }

  /**
   * Log the user out (Invalidate the token).
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request)
  {
    User::find($request->id)->update([
      'token' => null
    ]);

    return response()->json(['status' => true, 'message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh(Request $request)
  {
    try {
      $user = User::find($request->id);
      $token = JWTAuth::attempt(['username' => $user->username, 'password' => self::decryptText($user->token)]);
      return $this->respondWithToken($token);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      return response()->json(['error' => 'Token inválido'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json(['error' => 'No se encontró el token'], 401);
    }
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    return response()->json([
      'status' => true,
      'message' => 'Successfully',
      'user' => [
        'id' => JWTAuth::user()->id_users,
        'fullname' => JWTAuth::user()->fullname,
        'username' => JWTAuth::user()->username,
        'status' => JWTAuth::user()->status,
        'token' => JWTAuth::user()->token
      ],
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60
    ]);
  }
}
