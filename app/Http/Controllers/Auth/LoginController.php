<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        // $this->middleware('auth')->only('logout');
    }

    public function login_view()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required', 'email',
            'password' => 'required',
            ]);
            if(auth()->attempt(array('email' =>$request->email, 'password'=>$request->password))){
                if(auth()->user()->role == 'headmaster'){
                    return redirect()->route('home')->with('success', 'Login successful!');
                }else if(auth()->user()->role == 'teacher'){
                    return redirect()->route('home')->with('success', 'Login successful!');
                }else if(auth()->user()->role == 'student'){
                    return redirect()->route('home')->with('success', 'Login successful!');
                }else{
                    return redirect()->back()->with('error', 'Login failed!');
                }
            }else{
                // return redirect()->back()->with('error','invalid email or password');

                $notification = array('message' => 'invalid email or password', 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }
    }
}
