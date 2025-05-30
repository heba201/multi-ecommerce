<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product; 
use App\Models\ProductQuery;
use App\Models\OrderDetail; 
use auth;
class ProductController extends Controller
{
    public function productsBySlug($slug)
    {
        if (!Auth::check()) {
            session(['link' => url()->current()]);
        }
        $data=[];
        $data['product'] = Product::with('reviews', 'brand','category', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first(); //improve select only required fields
        if (!$data['product']){ ///  redirect to previous page with message
              }
             
        $product_id = $data['product'] -> id ;
        $product_categories_ids =  $data['product'] -> categories ->pluck('id'); // [1,26,7] get all categories that product on it

       $data['product_attributes'] =  Attribute::whereHas('options' , function ($q) use($product_id){
            $q -> whereHas('product',function ($qq) use($product_id){
                $qq -> where('product_id',$product_id);
            });
        })->get();
        $data['related_products'] =Product::where('category_id', $data['product']->category_id)
        ->where('id', '!=', $product_id)->where('published',1)->where('approved',1)->limit(20)-> latest() -> get();
        $product_queries = ProductQuery::where('product_id', $product_id)->where('customer_id', '!=', Auth::id())->latest('id')->paginate(3);
        $total_query = ProductQuery::where('product_id', $product_id)->count();
        $reviews = $data['product'] ->reviews()->paginate(3);

         // review status
         $review_status = 0;
         if (Auth::check()) {
             $OrderDetail = OrderDetail::with(['order' => function ($q) {
                 $q->where('user_id', Auth::id());
             }])->where('product_id', $product_id)->where('delivery_status', 'delivered')->first();
             $review_status = $OrderDetail ? 1 : 0;
         }

   return view('front.products-details', compact('data','product_queries','total_query','reviews','review_status'));
    }
}
