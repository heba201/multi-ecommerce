<?php

namespace App\Http\Controllers\Site;

use App\Models\Wishlist;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
class WishlistController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_arr=[];
    //    $products =  auth()->user()
    //         ->wishlist()
    //         ->latest()
    //         ->get(); // task for you basically we need to use pagination here
    $wishlists =  Wishlist::where('user_id', Auth::user()->id)->get();  

            $products =  Wishlist::where('user_id', Auth::user()->id)->get();  
            if($wishlists->count()>0){
             foreach($wishlists as $wishlist){
                $id_arr[]=$wishlist->product_id;
             }
            } 
       $products =Product::whereIn('id',$id_arr)->get();
       $newarriavel_products=Product::latest()->where('published', '1')->where('approved',1)->take(5)->get();
        $most_viewedproducts=Product::where('viewed','>',0)->where('published', '1')->where('auction_product', 0)->where('approved', '1')->orderBy("viewed","DESC")->get();
    return view('front.wishlist', compact('products','newarriavel_products','most_viewedproducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // if (! auth()->user()->wishlistHas(request('productId'))) {
        //     auth()->user()->wishlist()->attach(request('productId'));
        //     return response() -> json(['status' => true , 'wished' => true]);
        // }
        // return response() -> json(['status' => true , 'wished' => false]);  // added before we can use enumeration here
        $resp=[];
        if(auth()->user() == null) {
            $resp['msg']='not logged';
            $resp['icon']="error";
            $resp['redirect']=route('login');
            return $resp;
        }
        if(Auth::check()){
            $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();
            if($wishlist == null){
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::user()->id;
                $wishlist->product_id = $request->product_id;
                $wishlist->save();
            }
           // return view('frontend.partials.wishlist');
            $resp['msg']=tran('Product has been  added to  whish list successfully');
            $resp['icon']="success";
            $resp["wishlist_count"]=Wishlist::where('user_id', Auth::user()->id)->count();
            $resp["loadurl"]=route('wishlist.loadwishlist_box');
        }
       // return 0;
        return $resp;
    }

    /**
     * Destroy resources by the given id.
     *
     * @param string $productId
     * @return void
     */
    public function destroy($id)
    {
        $wishlist=Wishlist::where('product_id',$id)->first();
       if($wishlist){
        $wishlist->delete();
       }
       return redirect()->route('wishlist.products.index');
        //auth()->user()->wishlist()->detach(request('productId'));
    }

    public function loadwishlist_box()
    {
        $id_arr=[];
        $wishlists =  Wishlist::where('user_id', Auth::user()->id)->get();  
    
                $products =  Wishlist::where('user_id', Auth::user()->id)->get();  
                if($wishlists->count()>0){
                 foreach($wishlists as $wishlist){
                    $id_arr[]=$wishlist->product_id;
                 }
                } 
                $products =Product::whereIn('id',$id_arr)->get();
        return view('front.includes.wishlist_box',compact('products'));
    }
}