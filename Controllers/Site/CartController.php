<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Category;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Color;
use Auth;
use Session;
use Cookie;
class CartController extends Controller
{
    public function addToCart(Request $request,$from)
    {
        $product = Product::find($request->product_id);
        $carts = array();
        $data = array();
       $resp=[];
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            // if($request->session()->get('temp_user_id')) {
            //     $temp_user_id = $request->session()->get('temp_user_id');
            // } else {
            //     $temp_user_id = bin2hex(random_bytes(10));
            //     $request->session()->put('temp_user_id', $temp_user_id);
            // }
            // $data['temp_user_id'] = $temp_user_id;
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $resp['msg']='not logged';
            $resp['icon']="error";
            $resp['redirect']=route('login');
            return $resp;
        }

        $data['product_id'] = $product->id;
        $data['owner_id'] = $product->user_id;

        $str = '';
        $tax = 0;

        $imgstr='';
            if($request->has('selectimg')) {
                $imgstr = $request->selectimg;
            }
            $data['selectedimg'] =$imgstr;
            
        if($product->auction_product == 0){
            if($product->digital != 1 && $request->quantity < $product->min_qty) {
                // return array(
                //     'status' => 0,
                //     'cart_count' => count($carts),
                //     'modal_view' => view('front.includes.minQtyNotSatisfied', [ 'min_qty' => $product->min_qty ])->render(),
                //     'nav_cart_view' => view('front.includes.cart')->render(),
                // );
                $resp['msg']=tran('have to add minimum '.$product->min_qty.' products!');
                $resp['icon']="error";
            }
            
             //check the color enabled or disabled for the product
             if($request->has('color') && $from=="details") {
                $str = $request['color'];
            }

            if ($product->digital != 1) {
                //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
                // foreach (json_decode(Product::find($request->product_id)->choice_options) as $key => $choice) {
                //     if($str != null){
                //         $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                //     }
                //     else{
                //         $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                //     }
                // }
                if($from=="details") {
                    if($request->has('attribute_id')){
                for($i=0;$i<count($request['attribute_id']);$i++){
                    if($str != null){
                        $str .= '-'.str_replace(' ', '', $request['attribute_val'][$i]);
                    }else{
                        $str .= str_replace(' ', '', $request['attribute_val'][$i]);
                    }
                }
            }
            }
                if($from=="outer"){
                    if(count(json_decode($product->colors)) > 0){
                        $str=Color::where('code', json_decode($product->colors)[0])->first()->name;
                    }

                    if($product->choice_options != null){
                        foreach (json_decode($product->choice_options) as $key => $choice){
                             
                                foreach ($choice->values as $key => $value){
                                    if($key==0){
                            if($str != null){
                                $str .= '-'.str_replace(' ', '', $value);
                            }else{
                                $str .= str_replace(' ', '', $value);
                            }
                        }
                                }
                        }
                    }
                }
            }
       
            $data['variation'] = $str;
           // return $request['attribute_id_'.$choice->attribute_id] .'/'.$str;
        //    $resp['test']=$str;
        //    return $resp;
            $product_stock = $product->stocks->where('variant', $str)->first();
            
            if(!$product_stock){
                $resp['msg']=tran('Some thing wrong,please try again later !');
                $resp['icon']="error";
                return $resp;
            }

            $price = $product_stock->price;
            
            if($product->wholesale_product){
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if($wholesalePrice){
                    $price = $wholesalePrice->price;
                }
            }

            $quantity = $product_stock->qty;
            
            if($quantity < $request['quantity']) {
                // return array(
                //     'status' => 0,
                //     'cart_count' => count($carts),
                //     'modal_view' => view('front.cart.outOfStockCart')->render(),
                //     'nav_cart_view' => view('front.cart.cart')->render(),
                // );
                $resp['msg']=tran('This item is out of stock!');
                $resp['icon']="error";
            }

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            }
            elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if($product->discount_type == 'percent'){
                    $price -= ($price*$product->discount)/100;
                }
                elseif($product->discount_type == 'amount'){
                    $price -= $product->discount;
                }
            }

            //calculation of taxes
            foreach ($product->taxes as $product_tax) {
                if($product_tax->tax_type == 'percent'){
                    $tax += ($price * $product_tax->tax) / 100;
                }
                elseif($product_tax->tax_type == 'amount'){
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = $request['quantity'];
            $data['price'] = $price;
            $data['tax'] = $tax;
            //$data['shipping'] = 0;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;
        

            if ($request['quantity'] == null){
                $data['quantity'] = 1;
            }

            if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
                $data['product_referral_code'] = Cookie::get('product_referral_code');
            }

            if($carts && count($carts) > 0){
                $foundInCart = false;

                foreach ($carts as $key => $cartItem){
                    $cart_product = Product::where('id', $cartItem['product_id'])->first();
                    if($cart_product->auction_product == 1){
                        return array(
                            'status' => 0,
                            'cart_count' => count($carts),
                            'modal_view' => view('front.cert.auctionProductAlredayAddedCart')->render(),
                            'nav_cart_view' => view('front.cert.cart')->render(),
                        );
                    }

                    if($cartItem['product_id'] == $request->product_id) {
                        $product_stock = $cart_product->stocks->where('variant', $str)->first();
                        $quantity = $product_stock->qty;
                        if($quantity < $cartItem['quantity'] + $request['quantity']){
                            //return   view('frontend.cart.index');
                            $resp['msg']='redirect to cart';
                            $resp['icon']="";
                        }
                        if(($str != null && $cartItem['variation'] == $str) || $str == null){
                            $foundInCart = true;

                            $cartItem['quantity'] += $request['quantity'];

                            if($cart_product->wholesale_product){
                                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                                if($wholesalePrice){
                                    $price = $wholesalePrice->price;
                                }
                            }

                            $cartItem['price'] = $price;

                            $cartItem->save();
                            $resp['msg']=tran('Product has been  added to  cart successfully');
                            $resp['icon']="success";
                        }
                    }
                }
                if (!$foundInCart) {
                    Cart::create($data);
                    $resp['msg']=tran('Product has been  added to  cart successfully');
                    $resp['icon']="success";
                }
            }
            else{
                Cart::create($data);
                $resp['msg']=tran('Product has been  added to  cart successfully');
                $resp['icon']="success";
            }

            // if(auth()->user() != null) {
            //     $user_id = Auth::user()->id;
            //     $carts = Cart::where('user_id', $user_id)->get();
            // } else {
            //     $temp_user_id = $request->session()->get('temp_user_id');
            //     $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            // }
			$resp["cart_count"]=Cart::where('user_id', Auth::user()->id)->count();
            $resp["cart_dropdown_url"]=route('cartnav');
            $resp["cart_table_url"]=route('site.cart.loadcart_table');
          return   $resp;
        }
        else{
            $price = $product->bids->max('amount');

            foreach ($product->taxes as $product_tax) {
                if($product_tax->tax_type == 'percent'){
                    $tax += ($price * $product_tax->tax) / 100;
                }
                elseif($product_tax->tax_type == 'amount'){
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = 1;
            $data['price'] = $price;
            $data['tax'] = $tax;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;
        

            if(count($carts) == 0){
                Cart::create($data);
                $resp['msg']=tran('Product has been  added to  cart successfully');
                $resp['icon']="success";
            }
            // if(auth()->user() != null) {
            //     $user_id = Auth::user()->id;
            //     $carts = Cart::where('user_id', $user_id)->get();
            // } else {
            //     $temp_user_id = $request->session()->get('temp_user_id');
            //     $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            // }
            $resp["cart_count"]=Cart::where('user_id', Auth::user()->id)->count();
            return   $resp;
        }
       
    }

    public function removeFromCart(Request $request)
    {
        $resp=[];
            $id=$request->id;
            $cart_id=$id;
            if(Cart::where('id', $id)->first()){
            Cart::destroy($cart_id);
            if(auth()->user() != null) {
                $user_id = Auth::user()->id;
                $carts = Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = $request->session()->get('temp_user_id');
                $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            }

            $resp['msg']=tran('Item removed from cart.');
            $resp['icon']="success";
            $resp['url']=route('site.cart.index');
        }else{
            $resp['msg']=tran('Some Thing Wrong ,please try again later !');
            $resp['icon']="error";
        }
   // return redirect()->back()->with('success', 'Item removed from cart.');
   return $resp;
    }

    
     //updated the quantity for a cart item
     public function updateQuantity(Request $request)
     {
      try{
        $count=count($request->cartid);
       // qntcollection
       $total=0;

       $shipping = 0;
     $product_shipping_cost = 0;

       for($i=0;$i<$count;$i++){
        $cartItem = Cart::findOrFail($request->cartid[$i]);
        //return  $request->cartid[$i];
         if($cartItem['id'] == $request->cartid[$i]){
             $product = Product::find($cartItem['product_id']);
             $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
             $quantity = $product_stock->qty;
             $price = $product_stock->price;
             ////////////////////////////////
             $product_shipping_cost = $cartItem['shipping_cost'];
                        
             $shipping += $product_shipping_cost;
             ////////////////////////////////
             //discount calculation
             $discount_applicable = false;
 
             if ($product->discount_start_date == null) {
                 $discount_applicable = true;
             }
             elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                 strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                 $discount_applicable = true;
             }
 
             if ($discount_applicable) {
                 if($product->discount_type == 'percent'){
                     $price -= ($price*$product->discount)/100;
                 }
                 elseif($product->discount_type == 'amount'){
                     $price -= $product->discount;
                 }
             }
 
             if($quantity >= $request->qntcollection[$i]) {
                 if($request->qntcollection[$i] >= $product->min_qty){
                     $cartItem['quantity'] = $request->qntcollection[$i];
                 }
             }
 
             if($product->wholesale_product){
                 $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->qntcollection[$i])->where('max_qty', '>=', $request->qntcollection[$i])->first();
                 if($wholesalePrice){
                     $price = $wholesalePrice->price;
                 }
             }
 
             $cartItem['price'] = $price;
             $total+=$price*$cartItem['quantity'];
             $cartItem->save();
         }
        }
         if(auth()->user() != null) {
             $user_id = Auth::user()->id;
             $carts = Cart::where('user_id', $user_id)->get();
         } else {
             $temp_user_id = $request->session()->get('temp_user_id');
             $carts = Cart::where('temp_user_id', $temp_user_id)->get();
         }
         $resp["subtotal"]=$price*$cartItem['quantity'];
         $resp["cartinfo"]=$carts;
         $resp["total"]=single_price($total);
         $resp["shipping"]=single_price($shipping); 
         $resp["final_total"]=single_price($shipping+$total);
         return  $resp;
        }catch(Exception $e){
            return $e;
        }
     }
    
    
    public function viewCart()
    {
        $newarriavel_products=Product::latest()->where('published', '1')->where('approved', '1')->take(5)->get();
        $most_viewedproducts=Product::where('viewed','>',0)->where('published', '1')->where('auction_product', 0)->where('approved', '1')->orderBy("viewed","DESC")->get();
        return view('front.cart.index',compact('newarriavel_products','most_viewedproducts'));
    }
    public function applyCoupon(Request $request){
        $data=$request->all();
        $count=Coupon::where('coupon_code',$data['coupon'])->active()->count();
        if($count == 0){
            return redirect()->back()->with(['error'=>tran('Coupon is not valid')]);
        }else{
            return  Coupon::where('coupon_code',$data['coupon'])->active()->get();
            // return $request->coupon;
        }
        
    }

    public function loadcart_table()
    {
        return view('front.cart.cart_table');
    }
}
