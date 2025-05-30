<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Shop;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Cart;
use Str;
use DB;
use  App\Models\Page;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Session;
class HomeController extends Controller
{
    public function home()
     { 
     $trending_products=Product::where('trending',1)->where('published',1)->where('approved',1)->latest()->take(5)->get();
     $newarriavels = Product::orderByDesc('id')->where('published',1)->where('approved',1)->latest()->take(6)->get();
     $newarrivals_section = Product::orderByDesc('id')->where('published',1)->where('approved',1)->latest()->take(15)->get();

     $slider_products= $newarriavels->slice(0,3);
     $newarriavel_products= $newarriavels->slice(3,6);
     $featured_products=Product::where('featured',1)->where('published',1)->where('approved',1)->latest()->take(5)->get();
     $best_sellings=Product::where('published',1)->where('approved',1)->where('num_of_sale','>',0)->orderBy('num_of_sale', 'desc')->latest()->limit(5)->get();
     $trending_best_sellings=Product::where('published',1)->where('approved',1)->where('num_of_sale','>',0)->orderBy('num_of_sale', 'desc')->where('trending', '1')->latest()->limit(5)->get();
     $featured_categories=Category::where('featured',1)->active()->latest()->get();
     $categories=Category::latest()->active()->get();
     $brands=Brand::get();
     $topbrands=Brand::where('top',1)->get();
     $top_brands_id=[];
     if($topbrands ->count() > 0){
        foreach($topbrands as $br){
         $top_brands_id[]=$br->id;
        }
     }
     $topbrand_products = Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('approved', '1')->whereIn('brand_id', $top_brands_id)->latest()->take(5)->get();
     $most_viewedproducts=Product::where('viewed','>',0)->where('published', '1')->where('approved',1)->where('auction_product', 0)->where('trending', '1')->orderBy("viewed","DESC")->take(5)->get();
     $most_viewedproductstwo=Product::where('viewed','>',0)->where('published', '1')->where('approved',1)->where('auction_product', 0)->orderBy("viewed","DESC")->take(5)->get();
     $todays_deal_products=Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('todays_deal',1)->take(5)->get();
     
     
     return view('front.home',compact('trending_products','newarriavel_products','slider_products','featured_products','best_sellings','featured_categories','brands','topbrand_products','most_viewedproducts','categories','topbrands','newarrivals_section','most_viewedproductstwo','trending_best_sellings','todays_deal_products'));
        }

        public function product_brands(Request $request,$slug,$sort_by=null,$limit=null)
        { 
        $sort_by=$request->sort_by;
        $limit=$request->limit;
        $brand=Brand::where('slug',$slug)->first();
        $products_arr=Product::where('brand_id',$brand->id)->where('published',1)->where('approved',1);
        if(isset($sort_by) && $sort_by !=""){
            switch ($sort_by) {
                case 'newest':
                    $products_arr=$products_arr->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products_arr=$products_arr->orderBy('created_at', 'asc');
                    break;
                case 'price-asc':
                    $products_arr= $products_arr->orderBy('unit_price', 'asc');
                    break;
                case 'price-desc':
                    $products_arr= $products_arr->orderBy('unit_price', 'desc');
                    break;
                default:
                $products_arr=$products_arr->orderBy('id', 'desc');
                    break;
            }
        }
        $brand_products= $products_arr->paginate(10)->appends(request()->query());
        $limit=$request->limit;
        if(isset($limit) && $limit !=""){
            $brand_products = $brand_products->take($limit);
        }
        //return     $brand_products;
        return view('front.brand_products',compact('brand_products','brand','sort_by','slug','limit'));
        }
        

        
    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            if ($shop->verification_status != 0) {
                return view('front.seller_shop', compact('shop'));
            } else {
                return view('front.seller_shop_without_verification', compact('shop'));
            }
        }
        abort(404);
    }
    



    public function filter_shop(Request $request, $slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {

            if ($type == 'all-products') {
                $sort_by = $request->sort_by;
                $min_price = $request->min_price;
                $max_price = $request->max_price;
                $selected_categories = array();
                $brand_id = null;
                $rating = null;

                $conditions = ['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1];

                if ($request->brand != null) {
                    $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
                    $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
                }

                $products = Product::where($conditions);

                if ($request->has('selected_categories')) {
                    $selected_categories = $request->selected_categories;
                    $products->whereIn('category_id', $selected_categories);
                }

                if ($min_price != null && $max_price != null) {
                    $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
                }

                if ($request->has('rating')) {
                    $rating = $request->rating;
                    $products->where('rating', '>=', $rating);
                }

                switch ($sort_by) {
                    case 'newest':
                        $products->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $products->orderBy('created_at', 'asc');
                        break;
                    case 'price-asc':
                        $products->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $products->orderBy('unit_price', 'desc');
                        break;
                    default:
                        $products->orderBy('id', 'desc');
                        break;
                }

                $products = $products->paginate(24)->appends(request()->query());

                return view('front.seller_shop', compact('shop', 'type', 'products', 'selected_categories', 'min_price', 'max_price', 'brand_id', 'sort_by', 'rating'));
            }

            return view('front.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    
    public function quickview($id)
    {
        $product=Product::where('id',$id)->first();
        return view('front.includes.product-details',compact('product'));
    
    }
    public function cartnav()
    {
      
        return view('front.includes.cartnav');
    
    }
    public function showForgetPasswordForm()
      {
         return view('auth.passwords.email');
      }


      public function submitForgetPasswordForm(Request $request)
      {
        try{
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);
  
          $token = Str::random(64);
  
          DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
            ]);
  
          Mail::send('front.email.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password')
              ->from(env('MAIL_FROM_ADDRESS'));
          });
  
          return back()->with(['message'=>tran('We have e-mailed your password reset link!')]);
        }     catch (\Exception $ex) {
            //   return $ex;
            return back()->with(['error' => tran('An error occurred, please try again later')]);
        }
      } 

      public function showResetPasswordForm($token) { 
        return view('auth.passwords.reset', ['token' => $token]);
     }


     public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ],[
            'required' => tran('This field is required'),
          ]
        );
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with(['error'=>tran('Invalid token!')]);
          }
          if ($request->password == $request->password_confirmation) {
          $user = User::where('email', $request->email)
          ->update(['password' => Hash::make($request->password),'email_verified_at'=>date('Y-m-d h:m:s')]);
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
          return redirect()->route('login')->with(['message'=>tran('Your password has been changed!')]);
          }
      }

    public function reset_password_with_code(Request $request)
    {
         try{
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

              

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home')->with(['success' => tran('Password updated successfully')]);
            } else {
                
                 
               return view('auth.passwords.reset')->with(['warning' => tran("Password and confirm password didn't match")]);
            }
        } else {
           
            return view('auth.passwords.reset')->with(['error' => tran("Verification code mismatch")]);
        }
    }     catch (\Exception $ex) {
        return back()->with(['error' => tran('An error occurred, please try again later')]);
    }
    }

    public function vendor_listing($slug=null){
        if($slug !=""){
            $shops=Shop::where('slug','like','%'.$slug.'%')->paginate(10)->appends(request()->query());
        }else{
            $shops=Shop::paginate(10)->appends(request()->query());
        }
      
        return view('front.vendor_list',compact('shops','slug'));
    }
    public function get_page($page_type)
    {
        $page =  Page::where('type',$page_type)->first();
        if($page != null){
            return view('front.custom_page', compact('page'));
        }
        abort(404);
       
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('front.coupons', compact('coupons'));
    }

    public function all_flash_deals(){
        return view('front.flash_deals'); 
    }

    public function view_all($type){
        if($type=="best_sellings"){
            $products=Product::where('published',1)->where('approved',1)->where('num_of_sale','>',0)->orderBy('num_of_sale', 'desc')->where('auction_product', 0)->latest()->paginate(20)->appends(request()->query());
        }
        if($type=="featured_products"){
            $products=Product::where('featured',1)->where('published',1)->where('approved',1)->where('auction_product', 0)->latest()->paginate(20)->appends(request()->query());
        }
        if($type=="most_viewedproducts"){
            $products=Product::where('viewed','>',0)->where('published', '1')->where('approved',1)->where('auction_product', 0)->orderBy("viewed","DESC")->latest()->paginate(20)->appends(request()->query());
        }
        if($type=="trending_products"){
            $products=Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('trending',1)->latest()->paginate(20)->appends(request()->query());
        }
        
        if($type=="trending_best_sellings"){
            $products=Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('num_of_sale','>',0)->orderBy('num_of_sale', 'desc')->where('trending', '1')->latest()->paginate(20)->appends(request()->query());
        }
        
        if($type=="trending_most_viewedproducts"){
            $products=Product::where('viewed','>',0)->where('published', '1')->where('approved',1)->where('auction_product', 0)->where('trending', '1')->orderBy("viewed","DESC")->latest()->paginate(20)->appends(request()->query());
        }
       
        $topbrands=Brand::where('top',1)->get();
        $top_brands_id=[];
        if($topbrands ->count() > 0){
           foreach($topbrands as $br){
            $top_brands_id[]=$br->id;
           }
        }
        
        if($type=="trending_topbrand_products"){
        $products = Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('approved', '1')->where('trending', '1')->whereIn('brand_id', $top_brands_id)->latest()->paginate(20)->appends(request()->query());
        }
        
        if($type=="todays_deal_products"){
            $products=Product::where('published', '1')->where('approved',1)->where('auction_product', 0)->where('todays_deal',1)->latest()->paginate(20)->appends(request()->query());
        }
        if($type=="new_arrivals_products"){
        $products = Product::orderByDesc('id')->where('published',1)->where('approved',1)->latest()->paginate(20)->appends(request()->query());
        }
        return view('front.allproducts_bytype',compact('products','type'));
    }
}
