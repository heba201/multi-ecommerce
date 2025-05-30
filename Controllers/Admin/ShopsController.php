<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SellerRegistrationRequest;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessSetting;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
class ShopsController extends Controller
{
    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }
   
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
			if((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
				return back()->with(['error'=>tran('Admin or Customer can not be a seller')]);
			} if(Auth::user()->user_type == 'seller'){
				return back()->with(['error'=>tran('This user already a seller')]);
			}
            
        } else {
            return view('front.seller_form');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRegistrationRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/"," ", $request->shop_name));
            $shop->save();
            
            auth()->login($user, false);
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
            } else {
                $user->notify(new EmailVerificationNotification());
            }
            return redirect()->route('shop.index')->with(['success'=>tran('Your Shop has been created successfully!')]);
        }
        return back()->with(['error'=>tran('Sorry! Something went wrong.')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
