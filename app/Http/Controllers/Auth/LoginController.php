<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers {
        logout as performLogout;
    }

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
    * After logined
    */
    public function authenticated(Request $request, $user)
    {
        if($user->institution_id != 0) {
            $institution = $user->institution;
            // For Institution
            if(isset($request->prefix) && $institution->prefix == $request->prefix) {
                return redirect()->intended($this->redirectTo);
            }
        } elseif($user->institution_id == 0) {
            return redirect()->intended($this->redirectTo);
        }
        
        auth()->logout();
        return back()->with('warning', 'Wrong Credentials added!');
    }

    public function logout(Request $request)
    {
        if(auth()->user()->institution_id != 0) {
            $prefix = auth()->user()->institution->prefix;
            $this->performLogout($request);
            return redirect()->route('loginPage', $prefix);
        } else {
            $this->performLogout($request);
            return redirect()->route('loginPage', 'admin');
        }
    }
}
