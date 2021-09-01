<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function redirectTo()
    {   $userAuth = User::where('id', auth()->user()->id)->first();
        $token = Str::random(10);

        if ($userAuth->remember_token == null){
            DB::table('users')->where('id', $userAuth->id)->update([
                'remember_token' => $token
            ]);
            session()->flash('message_login_first');
            return '/';
        }else{

            return '/';
        }

    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect('/login');
    }
}
