<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Session;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        // $request->session()->forget('compare');
        //dd($request->session()->get('compare'));
        $categories = Category::all();
        $newarriavel_products=Product::latest()->where('published', '1')->where('approved',1)->take(5)->get();
        $most_viewedproducts=Product::where('viewed','>',0)->where('published', '1')->where('auction_product', 0)->where('approved', '1')->orderBy("viewed","DESC")->get();
        return view('front.compare', compact('categories','newarriavel_products','most_viewedproducts'));
    }

    //clears the session data for compare
    public function reset(Request $request)
    {
        $request->session()->forget('compare');
        return back();
    }

    //store comparing products ids in session
    public function addToCompare(Request $request)
    {
        
        $resp=[];
        if(auth()->user() == null) {
            $resp['msg']='not logged';
            $resp['icon']="error";
            $resp['redirect']=route('login');
            return $resp;
        }
        if($request->session()->has('compare')){
            $compare = $request->session()->get('compare', collect([]));
            if(!$compare->contains($request->product_id)){
                if(count($compare) == 3){
                    $compare->forget(0);
                    $compare->push($request->product_id);
                }
                else{
                    $compare->push($request->product_id);
                }
            }
        }
        else{
            $compare = collect([$request->product_id]);
            $request->session()->put('compare', $compare);
        }
            $rep['msg']=tran('Product has been added to compare list');
            $rep['icon']="success";
            $rep['compare_loadurl']=route('compare.loadcompare_box');
        return $rep;
    }

   

    function delete(Request $request){
        if($request->session()->has('compare')){
            $arr[]=$request->product_id;
            
            $compare = Session::get('compare');
               foreach($compare as $key=>$value){
                if($value ==$request->product_id){
                unset($compare[$key]);
                }
               }
         
               $resp=route('compare');
    }
    return $resp;
       
    }


    public function loadcompare_box()
    {
        return view('front.includes.compare_box');
    }
}
