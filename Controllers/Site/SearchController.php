<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Search;
use App\Models\ProductTranslation;
class SearchController extends Controller
{
    public function productsfilter(Request $request,$sort_by=null,$limit=null){
        // return $_POST;
        $brand_arr=[];
        $color_arr=[];
        $products=[];
        
        // {"_token":"inYztKINFkEaHvWlDc1Tg7K14PPgis4OeINyAMDw","min-value":"16","max-value":"173","brand":["1","2"],"colorchk":["#0000FF","#A52A2A"]}
        ////////////////////////////////////////////////////
         if ($request->has('min_value') && $request->has('max_value')) {
            $min_price = $request->min_value;
            $max_price = $request->max_value;
            $products_arr=Product::whereBetween('unit_price',[$min_price, $max_price]);
        }
        if ($request->has('brand')) {
        for($i=0;$i<count($request->brand);$i++){  
            $brand_arr[]=$request->brand[$i];
        }
        $products_arr=$products_arr->whereIn('brand_id',$brand_arr);
       }

       if (isset($_GET['query']) && $_GET['query'] != null) {
       $query= $_GET['query'];
       $word= $_GET['query'];
       $searchController = new SearchController;
       $searchController->store($request);

       $products_arr=Product::where('name', 'like', '%' . $word . '%')
       ->orWhere('tags', 'like', '%' . $word . '%')
       ->orWhereHas('product_translations', function ($q) use ($word) {
        $q->where('name', 'like', '%' . $word . '%');
    })
    ->orWhereHas('stocks', function ($q) use ($word) {
        $q->where('sku', 'like', '%' . $word . '%');
    });
 
    }

    $sort_by=$request->sort_by;
   
    if(isset($sort_by) && $sort_by !=""){
        $products_arr=new Product();
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


 $limit=$request->limit;
if(isset($limit) && $limit !=""){
    $products_arr =$products_arr ->take($limit);
}
$products= $products_arr;
$pro_id=[];
       if ($request->has('colorchk')) {
       // $products=[];
        if($products_arr->get()->count()>0){
            foreach ($products_arr->get() as $pro){
                if($pro->color !='[]'){
                    $colors=json_decode($pro->colors);
                    for($i=0;$i<count($request->colorchk);$i++){  
                        if(in_array($request->colorchk[$i],$colors)){
                          $pro_id[]=$pro->id;
                          //  $products[]=$pro; 
                        }
                    }
                    $pro_id=array_unique(array_values($pro_id));
                } 
            }
           
        } 
        $products_arr=$products_arr->whereIn('id',$pro_id);
      }
      $products_arr=$products_arr->paginate(24)->appends(request()->query());
      $products= $products_arr;
       /////////////////////////////////////////////////////////
      return view('front.filtered_products', compact('products','sort_by','limit'));
    }

    public function store(Request $request)
    {
        $search = Search::where('query', $_GET['query'])->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search;
            $search->query = $_GET['query'];
            $search->save();
        }
    }
}
