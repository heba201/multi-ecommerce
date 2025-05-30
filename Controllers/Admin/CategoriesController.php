<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use App\Utility\CategoryUtility;
use DB;
use Str;
use Cache;
class CategoriesController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create');
        $this->middleware(['permission:edit_product_category'])->only('edit');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }
   
    public function index()
    {
        // -> paginate(PAGINATION_COUNT)
        $sort_search =null;
        $categories = Category::orderBy('order_level', 'desc');
        // if ($request->has('search')){
        //     $sort_search = $request->search;
        //     $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        // }
      
       $categories = $categories->get();
        // $categories = Category::orderBy('id','DESC') -> get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        //  $categories =   Category::select('id','parent_id')->get();
        //  $parent_id = null; $spacing = ''; $tree_array = array();
        //  $categories_tree = Category::select('id', 'parent_id')->where('parent_id' ,'=', $parent_id)->orderBy('parent_id')->get();
        //  foreach ($categories_tree as $item){
        //     $tree_array[] = ['categoryId' => $item->id, 'categoryName' =>$spacing . $item->name] ;
        //     $tree_array = $this->getCategoryTree($item->id, $spacing . '--', $tree_array);
        // }
        //  return view('admin.categories.create',compact('categories','tree_array'));

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
            return view('admin.categories.create',compact('categories'));
    }

    public function store(MainCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
        $banner="";
        $icon="";
        $cover_image="";
        $category = new Category;
        $category->name = $request->name;
        $category->order_level = 0;
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        if ($request->has('banner')) {
            // $request->file('banner')
            $banner = uploadImage('categories', $request->banner);
        }
        if ($request->has('icon')) {
            $icon = uploadImage('categories', $request->icon);
        }
        if ($request->has('cover_image')) {
            $cover_image = uploadImage('categories', $request->cover_image);
        }
        $category->banner =  $banner;
        $category->icon = $icon;
        $category->cover_image = $cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
          if( $request->status == 0){
            $category->status=0;
          }
          
        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        DB::commit();
      
        return redirect()->route('admin.categories')->with(['success' => tran('Category has been inserted successfully')]);

    }catch (\Exception $ex) {
        DB::rollback();
    return redirect()->route('admin.categories')->with(['error' => 'An error occurred, please try again later.']);
    }

    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->orderBy('name','asc')
            ->get();
        if (!$category)
            return redirect()->route('admin.categories')->with(['error' => tran('This category does not exist')]);
        return view('admin.categories.edit', compact('category','categories'));
    }


    public function update($id, MainCategoryRequest $request)
    {
        try {
            //validation

            //update DB

            $category = Category::find($id);
            $cover_image="";
            $icon="";
            $banner="";
            if (!$category)
                return redirect()->route('admin.categories')->with(['error' => 'This category does not exist']);

                $category = Category::findOrFail($id);
                $banner_arr=explode("categories/",$category->banner);
                if(count($banner_arr) > 1){
                if($banner_arr[1] !=""){
                    $banner=$banner_arr[1];
                }
            }
                $icon_arr=explode("categories/",$category->icon);
               if(count($icon_arr) > 1){
                if($icon_arr[1] !=""){
                $icon_arr=$icon_arr[1];
                }
            }
                $cover_image_arr=explode("categories/",$category->cover_image);
                if(count($cover_image_arr) > 1){
                if($cover_image_arr[1] !=""){
                $cover_image=$cover_image_arr[1];
                }
            }
                DB::beginTransaction();   
                if($request->order_level != null) {
                    $category->order_level = $request->order_level;
                }

                if ($request->has('banner') && $request->banner !="") {
                    $banner = uploadImage('categories', $request->banner); 
                }
                if ($request->has('icon')  && $request->icon !="") {
                    $icon = uploadImage('categories', $request->icon);
                }
                if ($request->has('cover_image') && $request->cover_image !="") {
                    $cover_image = uploadImage('categories', $request->cover_image);
                }

                $category->digital = $request->digital;
                $category->name = $request->name;
                $category->banner = $banner;
                $category->icon = $icon;
                $category->cover_image = $cover_image;
                $category->meta_title = $request->meta_title;
                $category->meta_description = $request->meta_description;
                $category->status=$request->status;
                $previous_level = $category->level;
        
                if ($request->parent_id != "0") {
                    $category->parent_id = $request->parent_id;
        
                    $parent = Category::find($request->parent_id);
                    $category->level = $parent->level + 1 ;
                }
                else{
                    $category->parent_id = 0;
                    $category->level = 0;
                }
        
                if($category->level > $previous_level){
                    CategoryUtility::move_level_down($category->id);
                }
                elseif ($category->level < $previous_level) {
                    CategoryUtility::move_level_up($category->id);
                }
        
                if ($request->slug != null) {
                    $category->slug = strtolower($request->slug);
                }
                else {
                    $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
                }
        
        
                if ($request->commision_rate != null) {
                    $category->commision_rate = $request->commision_rate;
                }
        
                $category->save();  
                $products=$category->all_products();
                if(get_setting('display_products_according_to_the_status_of_their_categories') == 1) {
                 if($products->count() > 0){
                    foreach($products->get() as $product){
                         $product->published=$request->status;
                        $product->save();
                    }
                 }
                }
                DB::commit();
                Cache::forget('featured_categories');
            return redirect()->route('admin.categories')->with(['success' => tran('Category has been updated successfully')]);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.categories')->with(['error' => tran('Some thing wrong,please try again later')]);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
            $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('admin.categories')->with(['error' => tran('This Catgory is not found')]);
             $products=$category->products;
             $childrens=$category->childrens;
             $category_translations=$category->category_translations();
             if($products->count() == 0 && $childrens->count() == 0){
                $category->delete();
                $category_translations->delete();
                return redirect()->route('admin.categories')->with(['success' => tran('Category has been deleted successfully')]);
             }else return redirect()->route('admin.categories')->with(['error'=>tran('Category can not be deleted')]);
            
        } catch (\Exception $ex) {
            return redirect()->route('admin.categories')->with(['error' => tran('Some thing wrong,please try again later')]);
        }
    }

    function getCategoryTree($parent_id = null, $spacing = '', $tree_array = array()) {
        $categories = Category::select('id', 'parent_id')->where('parent_id' ,'=', $parent_id)->orderBy('parent_id')->get();
        foreach ($categories as $item){
            $tree_array[] = ['categoryId' => $item->id, 'categoryName' =>$spacing . $item->name] ;
            $tree_array = $this->getCategoryTree($item->id, $spacing . '--', $tree_array);
        }
        return $tree_array;
    }


    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }
    public function changeStatus(Request $request){
        $category = Category::findOrFail($request->id);
        $category->status = $request->status;
        $category->save();
        $products=$category->all_products();
        if(get_setting('display_products_according_to_the_status_of_their_categories') == 1) {
         if($products->count() > 0){
            foreach($products->get() as $product){
                 $product->published=$request->status;
                $product->save();
            }
         }
        }
        return 1; 
    }
}
