<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Enterprise;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  /**
   * Show the application's login form.
   *
   * @return \Illuminate\View\View
   */
  public function showLoginForm()
  {

    $enterprise = Company::where('ruc', '20480865198');
    return view('auth.login', compact('enterprise'));
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
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  public function username()
  {
    return 'username';
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
    $request->validate(User::$rulesLogin, User::$messagesLogin);
  }

  /**
   * Authenticate master user
   * @param  \Illuminate\Http\Request  $request
   * @return boolean
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  private function authenticateMasterUser(Request $request)
  {
    $user = User::where('username', $request->username)->first();
    $IsUserMaster = User::find($user->id_users)->hasRole('master');
    if ($user && password_verify($request->password, $user->password) && $IsUserMaster) {
      $request->session()->put('user', $user);
      $request->session()->put('userId', $user->id_users);
      $request->session()->put('role', 'master');
      $request->session()->put('branchId', null);
      Auth::login($user);
      return true;
    }
    return false;
  }
}
