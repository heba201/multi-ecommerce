<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Region;
use Hash;
use Auth;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.profile.index');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.profile');
        } else {
            return view('front.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        $user = Auth::user();
        $user->name = filter_var($request->name, FILTER_SANITIZE_STRING);
        $user->email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        $user->phone = filter_var($request->phone, FILTER_SANITIZE_STRING);
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make(filter_var($request->new_password, FILTER_SANITIZE_STRING));
        }
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user -> id,
            'phone'=>'required|unique:users,phone,'.$user -> id,
        ], [
            'required' => tran('This field is required'),
            'name.max'=>tran('The name must not be greater than 255 characters'),
            'email.email' => tran('The email must be a valid email address'),
            'email.unique' => tran('The email has already been taken'),
            'phone.unique'=>tran('The phone has already been taken')
        ]
    );
        if ($request->has('new_password')) {
            $request->validate([
            'confirm_password' =>'required_with:new_password|same:new_password'
        ], [
         'confirm_password.same' => tran('The confirm password and new password must match'),
        ]);
        }
        if ($request->has('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpg,png,jpeg|max:2048',
            ],[
                'photo.image' => tran('The photo must be an image'),   
            ]
        );
            $filepath= uploadImage('users', $request->photo);
            $user->avatar_original=$filepath;
        }
        $user->save();
        return back()->with(["success"=>tran('Your Profile has been updated successfully!')]);
    }
}
