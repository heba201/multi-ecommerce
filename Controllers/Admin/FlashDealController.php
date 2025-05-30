<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FlashDealRequest;
use App\Models\FlashDeal;
use App\Models\FlashDealTranslation;
use App\Models\FlashDealProduct;
use App\Models\Product;
use Illuminate\Support\Str;

class FlashDealController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_flash_deals'])->only('index');
        $this->middleware(['permission:add_flash_deal'])->only('create');
        $this->middleware(['permission:edit_flash_deal'])->only('edit');
        $this->middleware(['permission:delete_flash_deal'])->only('destroy');
        $this->middleware(['permission:publish_flash_deal'])->only('update_featured');
    } 
    public function index(Request $request)
    {
        $sort_search = null;
        $flash_deals = FlashDeal::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $flash_deals = $flash_deals->where('title', 'like', '%'.$sort_search.'%');
        }
        $flash_deals = $flash_deals->paginate(15);
        return view('admin.flash_deals.index', compact('flash_deals', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.flash_deals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlashDealRequest $request)
    {
        $flash_deal = new FlashDeal;
        $flash_deal->title = $request->title;
        $flash_deal->text_color = $request->text_color;
        $banner="";
       // $date_var= explode(" to ", $request->date_range);
        $date_var =explode(" - ",$request->date_range, 3);
        $flash_deal->start_date = strtotime($date_var[0]);
        $flash_deal->end_date   = strtotime( $date_var[1]);

        $flash_deal->background_color = $request->background_color;
        $flash_deal->title = $request->title;
        $flash_deal->slug = Str::slug($request->title).'-'.Str::random(5);
       
        if ($request->has('banner') && $request->banner !="") {
            $banner = uploadImage('flashdeals', $request->banner);
        }
        $flash_deal->banner = $banner;
        if($flash_deal->save()){
            foreach ($request->products as $key => $product) {
                $flash_deal_product = new FlashDealProduct;
                $flash_deal_product->flash_deal_id = $flash_deal->id;
                $flash_deal_product->product_id = $product;
                $flash_deal_product->save();

                $root_product = Product::findOrFail($product);
                $root_product->discount = $request['discount_'.$product];
                $root_product->discount_type = $request['discount_type_'.$product];
                $root_product->discount_start_date = strtotime($date_var[0]);
                $root_product->discount_end_date   = strtotime( $date_var[1]);
                $root_product->save();
            }
            return redirect()->route('flash_deals.index')->with(['success' =>tran('Flash Deal has been inserted successfully')]);
        }
        else{
            
            return back()->with(['error' =>tran('Something went wrong')]);
        }
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
    public function edit(Request $request, $id)
    {
        $lang           = $request->lang;
        $flash_deal = FlashDeal::findOrFail($id);
        
        return view('admin.flash_deals.edit', compact('flash_deal','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FlashDealRequest $request, $id)
    {
        $flash_deal = FlashDeal::findOrFail($id);

        $flash_deal->text_color = $request->text_color;

       // $date_var               = explode(" to ", $request->date_range);
        $date_var =explode(" - ",$request->date_range, 3);
        $flash_deal->start_date = strtotime($date_var[0]);
        $flash_deal->end_date   = strtotime( $date_var[1]);

        $flash_deal->background_color = $request->background_color;
        
        $flash_deal->title = $request->title;
          if (($flash_deal->slug == null) || ($flash_deal->title != $request->title)) {
              $flash_deal->slug = strtolower(str_replace(' ', '-', $request->title) . '-' . Str::random(5));
          }
       
          $banner= $flash_deal->banner;
        $flash_deal->banner = $request->banner;
        if ($request->has('banner') && $request->banner !="") {
            $banner = uploadImage('flashdeals', $request->banner);
        }
        $flash_deal->banner=$banner ;
        // return $flash_deal->flash_deal_products;
        foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product) {
            $flash_deal_product->delete();
        }
        if($flash_deal->save()){
            foreach ($request->products as $key => $product) {
                $flash_deal_product = new FlashDealProduct;
                $flash_deal_product->flash_deal_id = $flash_deal->id;
                $flash_deal_product->product_id = $product;
                $flash_deal_product->save();

                $root_product = Product::findOrFail($product);
                $root_product->discount = $request['discount_'.$product];
                $root_product->discount_type = $request['discount_type_'.$product];
                $root_product->discount_start_date = strtotime($date_var[0]);
                $root_product->discount_end_date   = strtotime( $date_var[1]);
                $root_product->save();
            }
            return back()->with(['success'=>'Flash Deal has been updated successfully']);
        }
        else{
            return back()->with(['error'=>'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flash_deal = FlashDeal::findOrFail($id);
        $flash_deal->flash_deal_products()->delete();
        $flash_deal->flash_deal_translations()->delete();
        FlashDeal::destroy($id);
        return redirect()->route('flash_deals.index')->with(['success'=>'FlashDeal has been deleted successfully']);
    }

    public function update_status(Request $request)
    {
        $flash_deal = FlashDeal::findOrFail($request->id);
        $flash_deal->status = $request->status;
        if($flash_deal->save()){
             
            return 1;
        }
        return 0;
    }

    public function update_featured(Request $request)
    {
        // foreach (FlashDeal::all() as $key => $flash_deal) {
        //     $flash_deal->featured = 0;
        //     $flash_deal->save();
        // }
        $flash_deal = FlashDeal::findOrFail($request->id);
        $flash_deal->featured = $request->featured;
        if($flash_deal->save()){
             
            return 1;
        }
        return 0;
    }

    public function product_discount(Request $request){
        $product_ids = $request->product_ids;
       // return $product_ids;
      return view('admin.flash_deals.flash_deal_discount', compact('product_ids'));
    }

    public function product_discount_edit(Request $request){
        $product_ids = $request->product_ids;
        $flash_deal_id = $request->flash_deal_id;
        //return $product_ids;
        return view('admin.flash_deals.flash_deal_discount_edit', compact('product_ids', 'flash_deal_id'));
    }
}
