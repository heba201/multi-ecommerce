<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Brand;
use App\Models\Category;
use DB;
use Str;
class BrandsController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_brands'])->only('index');
        $this->middleware(['permission:add_brand'])->only('create');
        $this->middleware(['permission:edit_brand'])->only('edit');
        $this->middleware(['permission:delete_brand'])->only('destroy');
    }
    
    public function index()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }


    public function store(BrandRequest $request)
    {
        DB::beginTransaction();
        $brand = new Brand;
        $logo="";
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        if ($request->has('logo')) {
            $logo = uploadImage('brands', $request->logo);
        }
        $brand->logo = $logo;
        if($request->top == 1){
            $brand->top = $request->top ;
        }
        DB::commit();
        $brand->save();
  
        return redirect()->route('admin.brands')->with(['success' => 'Brand has been inserted successfully']);
    }


    public function edit($id)
    {

        //get specific categories and its translations
        $brand = Brand::find($id);

        if (!$brand)
            return redirect()->route('admin.brands')->with(['error' => 'This brand is not found']);

        return view('admin.brands.edit', compact('brand'));

    }


    public function update($id, BrandRequest $request)
    {
        try {
            //validation
            //update DB
            $brand = Brand::findOrFail($id);
            $logo="";
            if (!$brand)
            return redirect()->route('admin.brands')->with(['error' =>tran('This brand is not found')]);
            DB::beginTransaction();
            $brand_arr=explode("brands/",$brand->logo);
            if(count($brand_arr) > 1){
                if($brand_arr[1] !=""){
                $logo=$brand_arr[1];
            }
        }
           
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
       
           
            if ($request->has('logo') && $request->logo !="") {
                $logo = uploadImage('brands', $request->logo);
                }
                $brand->logo = $logo;
                $brand->top = $request->top ;
                $brand->save();
            DB::commit();
            return redirect()->route('admin.brands')->with(['success' => tran('Brand has been updated successfully')]);

        } catch (\Exception $ex) {

            DB::rollback();
           // return $ex;
            return redirect()->route('admin.brands')->with(['error' => 'An error occurred, please try again later.']);
        }
    }


    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'This brand is not found']);
            $products=$brand->products;
            if($products->count() == 0 ){
            $brand->delete();
            return redirect()->route('admin.brands')->with(['success' => tran('Brand has been deleted successfully')]);
            }else return redirect()->route('admin.brands')->with(['error'=>tran('Brand can not be deleted')]);

        } catch (\Exception $ex) {
            return redirect()->route('admin.brands')->with(['error' => tran('An error occurred, please try again later.')]);
        }
    }
}
