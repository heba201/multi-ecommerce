<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\loginrequest;
use Auth;
use Session;
class loginController extends Controller
{
    //
    public function getlogin()
    {
        return view('admin.Auth.login');
    }
    public function login(loginrequest  $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;
        if (auth()->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
                     /////////////////////////////////////////////////////
                          // if remember me clicked . Values will be stored in $_COOKIE  array
			if(isset($_POST["remember"])) {
                //COOKIES for username
                setcookie ("dashboard_login",$_POST["email"],time()+ (10 * 365 * 24 * 60 * 60));
                //COOKIES for password
                setcookie ("dashboard_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
                } 
                ///////////////////////////////////////////////////////////////////
            if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                return redirect() -> route('admin.dashboard');
            } elseif (auth()->user()->user_type == 'seller') {
                return redirect()->route('dashboard');
            } else {
    
                if (session('link') != null) {
                    return redirect(session('link'));
                } else {
                    
                    return redirect()->route('home');
                }
            }

        }else{
            if(isset($_COOKIE["dashboard_login"])) {
                setcookie ("dashboard_login","");
            }
                if(isset($_COOKIE["dashboard_password"])) {
                setcookie ("dashboard_password","");
                                }
         return redirect()->back()->with(['error' => tran('There is an error in the email or password')]);
        }
      
    }

    public function logout()
    {
       
        if(auth()->user()->user_type=="seller"){
            auth()->logout();
            Session::flush();
            return redirect()->route('home');
            
        }if(auth()->user()->user_type=="admin"){
            auth()->logout();
            Session::flush();
            return redirect()->route('get.admin.login');
        }
       
        return redirect()->route('home'); 
        
    }
    private function getGaurd()
    {
        return auth();
    }
}
