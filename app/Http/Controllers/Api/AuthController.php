<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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
    try {
      $credentials = request(['username', 'password']);

      if (!$token = JWTAuth::attempt($credentials)) {
        ErrorLog::create([
          'date' => date('Y-m-d H:i:s'),
          'type' => 'login_failed',
          'source' => 'AuthController@login',
          'message' => 'Login failed for user: ' . $credentials['username'],
          'trace' => '',
          'data' => json_encode(['username' => $credentials['username']])
        ]);
        return response()->json(['status' => false, 'message' => 'Datos incorrectos'], 401);
      }

      if (!JWTAuth::user()->isActive()) {
        JWTAuth::setToken($token)->invalidate();
        return response()->json(['status' => false, 'message' => 'Usuario inactivo'], 401);
      }

      return $this->respondWithToken($token);
    } catch (\Exception $e) {
      ErrorLog::create([
        'date' => date('Y-m-d H:i:s'),
        'type' => 'login_error',
        'source' => 'AuthController@login',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'data' => json_encode(['username' => request('username')])
      ]);
      return response()->json(['status' => false, 'message' => 'Error interno del servidor'], 500);
    }
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
    try {
      if (JWTAuth::user()) {
        JWTAuth::invalidate(JWTAuth::getToken());
      }
      return response()->json(['status' => true, 'message' => 'Successfully logged out']);
    } catch (\Exception $e) {
      return response()->json(['status' => true, 'message' => 'Successfully logged out']);
    }
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh(Request $request)
  {
    try {
      $newToken = JWTAuth::refresh(JWTAuth::getToken());
      return $this->respondWithToken($newToken);
    } catch (TokenExpiredException $e) {
      return response()->json(['status' => false, 'message' => 'Token has expired'], 401);
    } catch (TokenInvalidException $e) {
      return response()->json(['status' => false, 'message' => 'Token is invalid'], 401);
    } catch (JWTException $e) {
      return response()->json(['status' => false, 'message' => 'Token not found'], 401);
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
    $user = JWTAuth::user();
    return response()->json([
      'status' => true,
      'message' => 'Successfully',
      'user' => [
        'id' => $user->id_users,
        'fullname' => $user->fullname,
        'username' => $user->username,
        'status' => $user->status,
      ],
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60
    ]);
  }
}
