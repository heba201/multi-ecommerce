<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Auth;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
        return view('seller.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.coupons.create');
    } 
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $user_id = Auth::user()->id;
        Coupon::create($request->validated() + [
            'user_id' => $user_id,
        ]);
        return redirect()->route('coupon.index')->with(['success'=>'Coupon has been saved successfully.']);
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
        $coupon = Coupon::findOrFail(decrypt($id));
        return view('seller.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());
        return redirect()->route('coupon.index')->with(['success'=>'Coupon has been updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::destroy($id);
        return redirect()->route('coupon.index')->with(['success'=>'Coupon has been deleted successfully.']);
    }

    public function get_coupon_form(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $products = \App\Models\Product::where('user_id', Auth::user()->id)->where('published',1)->get();
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
            $products = \App\Models\Product::where('user_id', Auth::user()->id)->where('published',1)->get();
            return view('admin.coupons.partials.product_base_coupon_edit',compact('coupon', 'products'));
        }
        elseif($request->coupon_type == "cart_base"){
            $coupon = Coupon::findOrFail($request->id);
            return view('admin.coupons.partials.cart_base_coupon_edit',compact('coupon'));
        }
    }
}
