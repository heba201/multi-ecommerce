<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\Product;
use App\Models\orderDetail;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use CoreComponentRepository;
use Str;
class AttributesController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_product_attributes'])->only('index');
        $this->middleware(['permission:edit_product_attribute'])->only('edit');
        $this->middleware(['permission:delete_product_attribute'])->only('destroy');

        $this->middleware(['permission:view_product_attribute_values'])->only('show');
        $this->middleware(['permission:edit_product_attribute_value'])->only('edit_attribute_value');
        $this->middleware(['permission:delete_product_attribute_value'])->only('destroy_attribute_value');

        $this->middleware(['permission:view_colors'])->only('colors');
        $this->middleware(['permission:edit_color'])->only('edit_color');
        $this->middleware(['permission:delete_color'])->only('destroy_color');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::with('attribute_values')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->name = $request->name;
        $attribute->save();
        return redirect()->route('attributes.index')->with(['success'=>'Attribute has been inserted successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['attribute'] = Attribute::findOrFail($id);
        $data['all_attribute_values'] = AttributeValue::with('attribute')->where('attribute_id', $id)->get();

        // echo '<pre>';print_r($data['all_attribute_values']);die;

        return view("admin.attribute.attribute_value.index", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang      = $request->lang;
        $attribute = Attribute::findOrFail($id);
        return view('admin.attribute.edit', compact('attribute','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->name = $request->name;
        $attribute->save();

        return back()->with(['success'=>tran('Attribute has been updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attributes_arr=[];
        $products=Product::get();
        if($products->count() > 0){
            foreach($products as $product){
                if ($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))){
                    $attributes_arr[]=$attribute->id;
                }
            }
        } 
        $attributes_arr=array_values(array_unique($attributes_arr));
        if(count($attributes_arr) == 0 && $attribute->attribute_values->count() == 0){
            Attribute::destroy($id);
            return redirect()->route('attributes.index')->with(['success'=>tran('Attribute has been deleted successfully')]);
        }else  return redirect()->route('attributes.index')->with(['error'=>tran('Attribute can not be deleted')]);
    }
    public function store_attribute_value(Request $request)
    {
        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        $attribute_value->save();
        return redirect()->route('attributes.show', $request->attribute_id)->with(['success'=>tran('Attribute value has been inserted successfully')]);

    }

    public function edit_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        return view("admin.attribute.attribute_value.edit", compact('attribute_value'));
    }

    public function update_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        
        $attribute_value->save();
        return back()->with(['success'=>tran('Attribute has been deleted successfullyAttribute value has been updated successfully')]);
    }

    public function destroy_attribute_value($id)
    {
        $attribute_values = AttributeValue::findOrFail($id);
       $attributes_values_arr=[];
        $products=Product::get();
        if($products->count() > 0){
            foreach($products as $product){
                if ($product->attributes != null && in_array($attribute_values->value, json_decode($product->attributes, true))){
                    $attributes_values_arr[]=$attribute_values->id;
                }
            }
        }
        $order_details=orderDetail::get();
        if($order_details->count() > 0){
            foreach($order_details as $order){
                $variation_arr=explode("-",$order->variation);
                if(in_array($attribute_values->value, $variation_arr)){
                    $attributes_values_arr[]=$attribute_values->id;
                }
            }
        }
        $attributes_values_arr=array_values(array_unique($attributes_values_arr));
        if(count($attributes_values_arr) == 0) {
            AttributeValue::destroy($id);
            return redirect()->route('attributes.show', $attribute_values->attribute_id)->with(['success'=>tran('Attribute value has been deleted successfully')]);
        }else  return redirect()->route('attributes.show', $attribute_values->attribute_id)->with(['error'=>tran('Attribute value can not be deleted')]);
        
    }
    
    public function colors(Request $request) {
        $sort_search = null;
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null){
            $colors = $colors->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        $colors = $colors->get();

        return view('admin.color.index', compact('colors', 'sort_search'));
    }
    
    public function store_color(Request $request) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:colors|max:255',
        ]);
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        $color->save();
        return redirect()->route('admin.colors')->with(['success'=>tran('Color has been inserted successfully')]);
    }
    
    public function edit_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        return view('admin.color.edit', compact('color'));
    }

    /**
     * Update the color.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:colors,code,'.$color->id,
        ]);
        
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        $color->save();
        return back()->with(['success'=>tran('Color has been updated successfully')]);
    }
    
    public function destroy_color($id)
    {
        $color_arr=[];
        $color=Color::find($id);
        $products=Product::get();
        $order_details=orderDetail::get();
        if($products->count() > 0){
            foreach($products as $product){
                if (in_array($color->code, json_decode($product->colors))){
                    $color_arr[]=$color->id;
                }
            }
        }
        if($order_details->count() > 0){
            foreach($order_details as $order){
                $variation_arr=explode("-",$order->variation);
                if(in_array($color->name, $variation_arr)){
                    $color_arr[]=$color->id;
                }
            }
            
        } 
        $color_arr=array_values(array_unique($color_arr));
        if(count($color_arr) == 0){
            Color::destroy($id);
            return redirect()->route('admin.colors')->with(['success'=>tran('Color has been deleted successfully')]);
        }else return redirect()->route('admin.colors')->with(['error'=>tran('Color can not be deleted')]);
        
       
    }
}
