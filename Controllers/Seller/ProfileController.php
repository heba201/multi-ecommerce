<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\SellerProfileRequest;
use App\Models\User;
use Auth;
use Hash;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SecondEmailVerifyMailManager;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses; 
        return view('seller.profile.index', compact('user','addresses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerProfileRequest $request , $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        if ($request->has('photo')) {
            $avatar = uploadImage('users', $request->photo);
            $user->avatar_original = $avatar;
    }
        $shop = $user->shop;
        if($shop){
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;

            $shop->save();
        }
        $user->save();
        return back()->with(['success'=>'Your Profile has been updated successfully!']);
    }


     // Form request
     public function update_email(Request $request)
     {
         $email = $request->email;
         if (isUnique($email)) {
             $this->send_email_change_verification_mail($request, $email);
             return back()->with(['success'=>'A verification mail has been sent to the mail you provided us with.']);
         }
         return back()->with(['error'=>'Email already exists!']);
     }

     // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = tran('Email already exists!');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }
 
    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = tran('Email Verification');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = tran('Verify your account');
        $array['link'] = route('seller.email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = tran("Email Second");

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = tran("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();
            if ($user != null) {
                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();
                auth()->login($user, true);
                return redirect()->route('dashboard')->with(['success'=>tran('Email Changed successfully')]);
            }
        }
        return redirect()->route('dashboard')->with(['error'=>tran('Email was not verified. Please resend your mail!')]);
    }
}
