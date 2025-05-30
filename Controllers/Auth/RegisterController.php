<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\SMSGateways\VictoryLinkSms;
use App\Http\Services\SMSServices;
use App\Http\Services\VerificationServices;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;
use App\Notifications\EmailVerificationNotification;
use DB;
use Session;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VerificationServices $sms_services)
    {
        $this->middleware('guest');
        $this -> sms_services = $sms_services;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'unique:users'],
        ], [
            'required' => tran('This field is required'),
            'name.max'=>tran('The name must not be greater than 255 characters'),
            'email.email' => tran('The email must be a valid email address'),
            'email.unique' => tran('The email has already been taken'),
            'phone.unique'=>tran('The phone has already been taken')
        ]
    );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {

             DB::beginTransaction();
            $verification = [];
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);

            if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                Session::put('success', tran('Registration successful.'));
            }
            else {
                try {
                    $user->sendEmailVerificationNotification();
                    Session::put('success', tran('Registration successful. Please verify your email.'));
                } catch (\Throwable $th) {
                    $user->delete();
                    Session::put('success', tran('Registration failed. Please try again later.'));
                }
            }
             DB::commit();
             return  $user;
        }catch(\Exception $ex){
            DB::rollback();
        }
    }
}
