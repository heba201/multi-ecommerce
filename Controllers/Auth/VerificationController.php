<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Carbon\Carbon;
use App\Models\User;
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verification_confirmation($code){
        $user = User::where('verification_code', $code)->first();
        if($user != null){
            $user->email_verified_at = Carbon::now();
            $user->save();
            auth()->login($user, true);
         if($user->user_type == 'seller') {
            return redirect()->route('dashboard')->with(['success'=>tran('Your email has been verified successfully')]);
        }
        }
        else {
            return redirect()->route('home')->with(['error'=>tran('Sorry, we could not verifiy you. Please try again')]);
        }
     return redirect()->route('home')->with(['success'=>tran('Your email has been verified successfully')]);
    }
}
