<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function editProfile()
    {
        $admin = auth()->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        //validate
        // db
        try {
            $admin = auth()->user();
            if($request->password != null && ($request->password == $request->password_confirmation)){
                $admin->password = bcrypt($request->password);
            }
        if ($request->has('avatar')) {
                $avatar = uploadImage('users', $request->avatar);
                $admin->avatar_original = $avatar;
        }
        
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->country_id    = $request->country_id;
        $admin->state_id      = $request->state_id;
        $admin->city_id       = $request->city_id;
        $admin->save(); 
            
            return redirect()->back()->with(['success' => tran('Your profile has been updated successfully')]);
        } catch (\Exception $ex) {
           // return $ex; 
            return redirect()->back()->with(['error' => tran('An error occured ,please try again later')]);

        }
    }
}
