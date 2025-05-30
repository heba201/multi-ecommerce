<?php

namespace App\Http\Controllers\Seller;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\Image;
use Carbon\Carbon;
use Combinations;
use Artisan;
use Auth;
use Str;
use App\Models\FollowSeller;
use App\Models\Shop;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Utility\NotificationUtility;
use DB;
class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;

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
    }

    public function index(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->where('auction_product', 0)->where('wholesale_product', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->get();
        return view('seller.product.products.index', compact('products', 'search'));
    }

    public function create(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
        return view('seller.product.products.create', compact('categories'));
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

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        $request->merge(['meta_img' => $request->meta_img]);
        $request->merge(['pdf' => $request->pdf]); 
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);
        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

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
        DB::commit();

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }
     ////////////////////////////////////////////////////////////////
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('seller.products')->with(['success' => 'Product has been inserted successfully']);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {  
            return back()->with(['error'=>'This product is not yours.']);
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->active()->get();
        return view('seller.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        //Product
           $request->merge(['thumbnail_img' => $request->meta_img]);
           $request->merge(['meta_img' => $request->meta_img]);
           $request->merge(['pdf' => $request->pdf]); 
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }
        $request->merge(['product_id' => $product->id]);
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }
        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

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

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return back()->with(['success'=>'Product has been updated successfully']);
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
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('admin.products.general.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
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
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('admin.products.general.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
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

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if (addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 2;
            }
        }
        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function duplicate($id)
    {
        $product = Product::find($id);
        if (Auth::user()->id != $product->user_id) {
            return back()->with(['error'=>tran('This product is not yours.')]);
        }
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                
                return back()->with(['error'=>tran('Please upgrade your package.')]);
            }
        }

        if (Auth::user()->id == $product->user_id) {
            $product_new = $product->replicate();
            $product_new->slug = $product_new->slug . '-' . Str::random(5);
            $product_new->save();

            //Product Stock
            $this->productStockService->product_duplicate_store($product->stocks, $product_new);

            //VAT & Tax
            $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

            return redirect()->route('seller.products')->with(['success'=>tran('Product has been duplicated successfully')]);
        } else {
            return back()->with(['error'=>tran('This product is not yours.')]);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if(!$product) return redirect()->back()->with(['error'=>tran('Product is not found')]);
        if (Auth::user()->id != $product->user_id) {
            return back()->with(['error'=>tran('This product is not yours.')]);
        }
        $orderDetails=$product->orderDetails;
        if( $orderDetails->count() == 0){
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->images()->delete();
        } return back()->with(['error'=>tran('Product can not be deleted')]);
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back()->with(['success'=>tran('Product has been deleted successfully')]);
        } else {
            return back()->with(['error'=>tran('An error occured , please try again later')]);
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }
}
