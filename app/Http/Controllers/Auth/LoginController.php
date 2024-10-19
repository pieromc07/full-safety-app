<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
    $this->middleware('auth')->only('logout');
  }


  /**
   * Authenticate the user instance.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */

  public function login(Request $request)
  {
    $this->validateLogin($request);

    $user = User::where('username', $request->username)->first();

    if (!$user) {
      return redirect()->route('login')->withErrors(['username' => 'Usuario no encontrado'])->withInput();
    }

    if (!$user->isActive()) {
      return redirect()->route('login')->withErrors(['username' => 'Usuario inactivo'])->withInput();
    }
    if (password_verify($request->password, $user->password)) {
      Auth::login($user);
      return redirect()->intended($this->redirectPath());
    } else {
      return redirect()->route('login')->withErrors(['password' => 'Contraseña incorrecta'])->withInput();
    }
  }

  /**
   * Validate the user login request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function validateLogin(Request $request)
  {
    $request->validate(User::$rulesLogin, User::$rulesMessagesLogin);
  }



  public function username()
  {
    return 'username';
  }
}
