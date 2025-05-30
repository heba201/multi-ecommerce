<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Cart;
use Session;
use URL;
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
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (auth()->attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {

                // if remember me clicked . Values will be stored in $_COOKIE  array
			if( isset($_POST["remember"])) {
                //COOKIES for username
                setcookie ("user_login",$_POST["email"],time()+ (10 * 365 * 24 * 60 * 60));
                //COOKIES for password
                setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
                } 
                if (auth()->user()->user_type == 'customer') {
                if (session('link') != null) {
                    return redirect(session('link'));
                }else  return redirect()->route('home');
            }
            if (auth()->user()->user_type == 'seller') {
            return redirect()->route('home');
            }
            elseif(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                return redirect()->route('admin.dashboard');
                } 

            
        }else{
            if(isset($_COOKIE["user_login"])) {
                setcookie ("user_login","");
            }
                if(isset($_COOKIE["userpassword"])) {
                setcookie ("userpassword","");
                                }
             return redirect("login")->with(["error"=>trans('Oppes! You have entered invalid credentials')]);
            // return 'Oppes! You have entered invalid credentials';
        }
        
    }

    public function redirectToProvider($provider)
    {
        if (request()->get('query') == 'mobile_app') {
            request()->session()->put('login_from', 'mobile_app');
        }
        if ($provider == 'apple') {
            return Socialite::driver("sign-in-with-apple")
                ->scopes(["name", "email"])
                ->redirect();
        }
        return Socialite::driver($provider)->redirect();
    }



    public function handleProviderCallback(Request $request, $provider)
    {
        if (session('login_from') == 'mobile_app') {
            return $this->mobileHandleProviderCallback($request, $provider);
        }
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with(['error'=>$e.tran('Something Went wrong. Please try again.')]);
        }

        //check if provider_id exist
        $existingUserByProviderId = User::where('provider_id', $user->id)->first();

        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->save();
            //proceed to login
            auth()->login($existingUserByProviderId, true);
        } else {
            //check if email exist
            $existingUser = User::where('email', '!=', null)->where('email', $user->email)->first();

            if ($existingUser) {
                //update provider_id
                $existing_User = $existingUser;
                $existing_User->provider_id = $user->id;
                $existing_User->provider = $provider;
                $existing_User->access_token = $user->token;
                $existing_User->save();

                //proceed to login
                auth()->login($existing_User, true);
            } else {
                //create a new user
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->email_verified_at = date('Y-m-d Hms');
                $newUser->provider_id = $user->id;
                $newUser->provider = $provider;
                $newUser->access_token = $user->token;
                $newUser->save();
                //proceed to login
                auth()->login($newUser, true);
            }
        }

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        if (session('link') != null) {
            return redirect(session('link'));
        } else {
            if (auth()->user()->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('home');
        }
    }

    public function username(){
        return 'mobile';
    }
}
