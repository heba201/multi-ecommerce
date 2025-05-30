<?php

namespace App\Http\Controllers\Admin;
use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\ProductImagesRequest;
use App\Http\Requests\ProductPriceValidation;
use App\Http\Requests\ProductStockRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\Tag;
use App\Models\AttributeValue;
use App\Models\Cart;
use CoreComponentRepository;
use DB;
use Artisan;
use Cache;
use Str;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;

class ProductsController extends Controller
{
   
    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;

         // Staff Permission Check
         $this->middleware(['permission:add_new_product'])->only('create');
         $this->middleware(['permission:show_all_products'])->only('all_products');
         $this->middleware(['permission:show_in_house_products'])->only('admin_products');
         $this->middleware(['permission:show_seller_products'])->only('seller_products');
         $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
         $this->middleware(['permission:product_duplicate'])->only('duplicate');
         $this->middleware(['permission:product_delete'])->only('destroy');
    }
    public function admin_products(Request $request)
    {
        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->get();
        return view('admin.products.general.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }


    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('admin.products.general.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->paginate(15);
        $type = 'All';

        return view('admin.products.general.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
        return view('admin.products.general.create',compact('categories'));
    }


    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }
    
       $combinations = (new CombinationService())->generate_combination($options);
    //    return $combinations;
         return view('admin.products.general.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function store(ProductRequest $request)
    {
    
     try{
        //////////////////////with new columns and tables//////////////////////////////////////////////
        DB::beginTransaction();
        $request->merge(['meta_img' => $request->meta_img]);
        $request->merge(['pdf' => $request->pdf]); 
 
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        
        $request->merge(['product_id' => $product->id]);
            // Product Translations
          //save translations
          $product->name = $request->name;
          $product->description = $request->description;
          $product->unit = $request->unit;
          $product->save();
          $updatecol=Product::where('id',$product->id)->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'description' => $request->description
          ]);

         // product images
         if ($request->has('document') && count($request->document) > 0) {
            foreach ($request->document as $image) {
                Image::create([
                    'product_id' => $product->id,
                    'photo' => $image,
                ]);
            }
        }
       
           //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);
          DB::commit();
        
        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }
        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        /////////////////////////////////////////////////////////////////////
       return redirect()->route('admin.products')->with(['success' => tran('Product has been inserted successfully')]);
    }catch(\Exception $ex){
        DB::rollback();
      //   return  $ex;
        return redirect()->route('admin.products')->with(['error' => tran('some thing wrong')]);
    }
    }



    public function getPrice($product_id){

        return view('admin.products.prices.create') -> with('id',$product_id) ;
    }

    public function saveProductPrice(ProductPriceValidation $request){

        try{

            Product::whereId($request -> product_id) -> update($request -> only(['price','special_price','special_price_type','special_price_start','special_price_end']));

            return redirect()->route('admin.products')->with(['success' => 'تم التحديث بنجاح']);
        }catch(\Exception $ex){
            return redirect()->route('admin.products')->with(['error' => 'some thing wrong']);

        }
    }



    public function getStock($product_id){

        return view('admin.products.stock.create') -> with('id',$product_id) ;
    }

    public function saveProductStock (ProductStockRequest $request){


            Product::whereId($request -> product_id) -> update($request -> except(['_token','product_id']));

            return redirect()->route('admin.products')->with(['success' => 'تم التحديث بنجاح']);

    }

    public function addImages($product_id){
        return view('admin.products.images.create')->withId($product_id);
    }

    //to save images to folder only
    public function saveProductImages(Request $request ){

        $file = $request->file('dzfile');
        $filename = uploadImage('products', $file);

        return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }

    public function saveProductImagesDB(ProductImagesRequest $request){

        try {
            // save dropzone images
            if ($request->has('document') && count($request->document) > 0) {
                foreach ($request->document as $image) {
                    Image::create([
                        'product_id' => $request->product_id,
                        'photo' => $image,
                    ]);
                }
            }

            return redirect()->route('admin.products')->with(['success' => 'تم التحديث بنجاح']);

        }catch(\Exception $ex){

        }
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        // $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
           
        if (!$product)
            return redirect()->route('admin.products')->with(['error' => 'This product is not found']);
        return view('admin.products.general.edit', compact('categories','product'));

    }

    public function admin_product_edit(Request $request, $id)
    {
        // CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
           
        return view('admin.products.general.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
               
        return view('admin.products.general.edit', compact('product', 'categories', 'tags', 'lang'));
    }


    public function sku_combination_edit(Request $request)
    {
     
        $product = Product::findOrFail($request->id);
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }
        $product_name = $request->name;
        $unit_price = $request->unit_price;
       
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }
        $combinations = (new CombinationService())->generate_combination($options);
        return view('admin.products.general.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }


    public function update(ProductRequest $request, Product $product)
    {
        //Product
        $request->merge(['thumbnail_img' => $request->meta_img]);
        $request->merge(['meta_img' => $request->meta_img]);
        $request->merge(['pdf' => $request->pdf]); 
       // return $_POST;
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        // product images
        if ($request->has('document') && count($request->document) > 0) {
            foreach ($product->images as $key => $image) {
                $image->delete();
            }
            foreach ($request->document as $image) {
                Image::create([
                    'product_id' => $product->id,
                    'photo' => $image,
                ]);
            }
        }
        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }
        $request->merge(['product_id' => $product->id]);
      $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->back()->with(['success' => tran('Product has been updated successfully')]);

    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }
    
    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
         if(!$product) return redirect()->back()->with(['error'=>tran('Product is not found')]);
        $orderDetails=$product->orderDetails;
        if( $orderDetails->count() == 0){
            $product->product_translations()->delete();
            $product->stocks()->delete();
            $product->taxes()->delete();
            $product->images()->delete();
            $product->flash_deal_product()->delete();
        }else  return back()->with(['error'=>tran('Product can not be deleted')]);
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return back()->with(['success'=>tran('Product has been deleted successfully')]);
        } else {
            return back()->with(['error'=>tran('An error occured , please try again later')]);
        }
    }
}
