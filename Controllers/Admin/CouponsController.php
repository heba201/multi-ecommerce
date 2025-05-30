<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\AttributeRequest;
use App\Models\Coupon;
use App\Models\User;
use DB;
class CouponsController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_coupons'])->only('index');
        $this->middleware(['permission:add_coupon'])->only('create');
        $this->middleware(['permission:edit_coupon'])->only('edit');
        $this->middleware(['permission:delete_coupon'])->only('destroy');
    }
    
    public function index()
    {
        $coupons = Coupon::where('user_id', User::where('user_type', 'admin')->first()->id)->orderBy('id','desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }
    public function store(CouponRequest $request)
    {
        try {
            $user_id = User::where('user_type', 'admin')->first()->id;
            Coupon::create($request->validated() + [
                'user_id' => $user_id,
            ]);
        return redirect()->route('admin.coupons')->with(['success' =>tran('Coupon has been added successfully')]);
    } catch (\Exception $ex) {
        return redirect()->route('admin.coupons')->with(['error' => tran('An error occured,please try again later')]);
    }
    }
    public function edit($id)
    {

        $coupon = Coupon::findOrFail(decrypt($id));
        if (!$coupon)
            return redirect()->route('admin.coupons')->with(['error' => tran('This coupon is not found')]);

        return view('admin.coupons.edit', compact('coupon'));

    }
    public function update($id,CouponRequest $request,Coupon $coupon)
    {
        try {
        $coupon = Coupon::find($id);
        if (!$coupon)
            return redirect()->route('admin.coupons')->with(['error' => tran('This coupon is not found')]);
            $coupon->update($request->validated());
            return redirect()->route('admin.coupons')->with(['success' => tran('Coupon has been updated successfully')]);
        } catch (\Exception $ex) {
            // return $ex;
            return redirect()->route('admin.coupons')->with(['error' => tran('An error occured,please try again later')]);
        }
    }
    public function destroy($id)
    {
        try { 
            $coupon = Coupon::find($id);
        if (!$coupon)
        return redirect()->route('admin.coupons')->with(['error' => tran('This coupon is not found')]);
            $coupon->delete();
            return redirect()->route('admin.coupons')->with(['success' =>tran('Coupon has been deleted successfully')]);
        } catch (\Exception $ex) {
            return redirect()->route('admin.coupons')->with(['error' => tran('An error occured,please try again later')]);
        }
    }



    public function get_coupon_form(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $admin_id = User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('admin.coupons.partials.product_base_coupon', compact('products'));
        }
        elseif($request->coupon_type == "cart_base"){
            return view('admin.coupons.partials.cart_base_coupon');
        }
    }


    public function get_coupon_form_edit(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);
            $admin_id = User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('admin.coupons.partials.product_base_coupon_edit',compact('coupon', 'products'));
        }
        elseif($request->coupon_type == "cart_base"){
            $coupon = Coupon::findOrFail($request->id);
           return view('admin.coupons.partials.cart_base_coupon_edit',compact('coupon'));
        }
    }

}
