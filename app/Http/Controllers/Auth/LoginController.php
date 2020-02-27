<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Request;

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
    public function login(Request $request)
    {
        //$credentials = $request->only(['email', 'password']);
        if(!isset($request->email) or !isset($request->password))
            return view('auth.login');
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            // Authentication passed...
            // create api_token when user login
            $user = Auth::user();
            do{ //loop for make sure that is unique
                $token = Str::random(80);
                $check = User::where('api_token',$token)->first();
                if(!isset($check->id))
                    break;
            }while (true);
            $user->api_token = $token;
            $user->save();
            
            return redirect()->intended('/post');
        }
        return view('auth.login');
        
    }
}
