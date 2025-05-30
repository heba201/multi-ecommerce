<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function productsBySlug(Request $request,$slug,$sort_by=null,$limit=null)
    {
        $data = [];
        $products_arr=[];
        $sort_by=$request->sort_by;
        $limit=$request->limit;
        $data['category'] = Category::where('slug', $slug)->first();

        if ($data['category']){
            $data['products'] = $data['category']->products();
        }
        if(!isset($sort_by) && $sort_by ==""){
            $data['products'] = $data['category']->products()->paginate(10)->appends(request()->query());
        }
        //return  $data['products'];
        if(isset($sort_by) && $sort_by !=""){
        switch ($sort_by) {
            case 'newest':
                $products_arr=$data['products']->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products_arr=$data['products']->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products_arr= $data['products']->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products_arr= $data['products']->orderBy('unit_price', 'desc');
                break;
            default:
            $products_arr=$data['products']->orderBy('id', 'desc');
                break;
        }
        $data['products'] =$products_arr->paginate(10)->appends(request()->query());
    }
    $limit=$request->limit;
    if(isset($limit) && $limit !=""){
        $data['products'] = $data['products']->take($limit);
    }
        return view('front.products',compact('data','sort_by','slug','limit'));
    }
}
