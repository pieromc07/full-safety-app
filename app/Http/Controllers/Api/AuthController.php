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
  public function __construct()
  {
    // $this->middleware('auth:api', ['except' => ['login']]);
  }

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
    User::find(JWTAuth::user()->id)->update([
      'token' => $token
    ]);

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
  public function refresh()
  {
    return $this->respondWithToken(JWTAuth::refresh());
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
      'user' => JWTAuth::user(),
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60
    ]);
  }
}
