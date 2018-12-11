<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Company;

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
    
    // protected $redirectTo = '/dashboard';

    protected function redirectTo()
    {
        
        $company = Company::where('created_by', Auth::id())->get();
        if(count($company)<1){
            return 'register-company';
        }
        
        return 'dashboard';
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userlogout']]);
    }
    
    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
