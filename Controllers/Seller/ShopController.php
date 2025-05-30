<?php

namespace App\Http\Controllers\Seller;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use App\Models\State;
use App\Models\City;
use App\Models\Region;
use Auth;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
       
        $states = State::where('status', 1)->where('country_id', $shop->country_id)->get();
        $cities = City::where('status', 1)->where('state_id', $shop->state_id)->get();
    return view('seller.shop', compact('shop','states','cities'));
    }

    public function update(ShopRequest $request)
    {
        $shop = Shop::find($request->shop_id);
        $logo = $request->logo;
        if ($request->has('logo') && $request->logo !="") {
            $logo=uploadImage('shops', $request->logo);
        }
        if ($request->has('name') && $request->has('address')) {
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }

            $shop->name            = $request->name;
            $shop->country_id      =$request->country_id;
            $shop->state_id        =$request->state_id;
            $shop->city_id          =$request->city_id;
            $shop->region_id          =$request->region_id;
            $shop->address          = $request->address;
            $shop->phone            = $request->phone;
            $shop->slug             = preg_replace('/\s+/', '-', $request->name) . '-' . $shop->id;
            $shop->meta_title       = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo=$logo;
        }

/////////////////////////////////////////////////////////////////////////////
$banner_full1_arr=[];
$sliders_arr=[];
$banners_half_width_arr=[];
$banner_full_width_2_arr=[];
 
$top_banner=$shop->top_banner;
$sliders_str=$shop->sliders;
$banner_full_width_1=$shop->banner_full_width_1;
$banner_full1_str=$shop->banner_full_width_1;
$banners_half_width_str=$shop->banners_half_width;
$banner_full_width_2_str=$shop->banner_full_width_2;
$banners_half_width=$shop->banners_half_width;
if ($request->has('top_banner') && $request->top_banner !="") {
    $top_banner=uploadImage('shops', $request->top_banner);
}

if ($request->has('sliders') ) {
    for($i=0;$i<count($request->sliders);$i++){
    if($request->sliders[$i] !=""){
    $sliders=uploadImage('shops', $request->sliders[$i]);
    $sliders_arr[]=$sliders;
        }
    }
    $sliders_str=implode(",",$sliders_arr);
}


if ($request->has('banner_full_width_1')) {
    for($i=0;$i<count($request->banner_full_width_1);$i++){
        if($request->banner_full_width_1[$i] !=""){
        $banner_full_width_1=uploadImage('shops',$request->banner_full_width_1[$i]);
        $banner_full1_arr[]=$banner_full_width_1;
        }
    }
    $banner_full1_str=implode(",",$banner_full1_arr);
}

if ($request->has('banners_half_width')) {
    for($i=0;$i<count($request->banners_half_width);$i++){
        if($request->banners_half_width[$i] !=""){
    $banners_half_width=uploadImage('shops', $request->banners_half_width[$i]);
    $banners_half_width_arr[]=$banners_half_width;
    }
    }
     $banners_half_width_str=implode(",",$banners_half_width_arr);
}

if ($request->has('banner_full_width_2')) {
    for($i=0;$i<count($request->banner_full_width_2);$i++){
        if($request->banner_full_width_2[$i] !=""){   
    $banner_full_width_2=uploadImage('shops', $request->banner_full_width_2[$i]);
    $banner_full_width_2_arr[]=$banner_full_width_2;
        }
    }

    $banner_full_width_2_str=implode(",",$banner_full_width_2_arr);
}
 
//////////////////////////////////////////////////////////////////////////////////
        if ($request->has('delivery_pickup_longitude') && $request->has('delivery_pickup_latitude')) {

            $shop->delivery_pickup_longitude    = $request->delivery_pickup_longitude;
            $shop->delivery_pickup_latitude     = $request->delivery_pickup_latitude;
        } elseif (
            $request->has('facebook') ||
            $request->has('google') ||
            $request->has('twitter') ||
            $request->has('youtube') ||
            $request->has('instagram')
        ) {
            $shop->facebook = $request->facebook;
            $shop->instagram = $request->instagram;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        } elseif (
            $request->has('top_banner') ||
            $request->has('sliders') || 
            $request->has('banner_full_width_1') || 
            $request->has('banners_half_width') || 
            $request->has('banner_full_width_2')
        ) {
            $shop->top_banner = $top_banner;
            $shop->sliders = $sliders_str;
            $shop->banner_full_width_1 = $banner_full1_str;
            $shop->banners_half_width = $banners_half_width_str;
            $shop->banner_full_width_2 = $banner_full_width_2_str;
        }

        if ($shop->save()) {
           
            return back()->with(['success'=>'Your Shop has been updated successfully!']);
        }

       
        return back()->with(['error'=>'Sorry! Something went wrong.']);
    }

    public function verify_form ()
    {
        if (Auth::user()->shop->verification_info == null) {
            $shop = Auth::user()->shop;
            return view('seller.verify_form', compact('shop'));
        } else {
            
            return back()->with(['error'=>'Sorry! You have sent verification request already.']);
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label; 
                $item['value'] = uploadImage('shops',$request['element_' . $i]);
            }
            array_push($data, $item);
            $i++;
        }
        $shop = Auth::user()->shop;
        $shop->verification_info = json_encode($data);
        if ($shop->save()) {
           
            return redirect()->route('dashboard')->with(['success'=>tran('Your shop verification request has been submitted successfully!')]);
        }

        return back()->with(['error'=>tran('Sorry! Something went wrong.')]);
    }

    public function show()
    {
    }
}
