@extends('seller.layouts.app')
@section('content')
<link id="dropzone-css" href="{{asset('css/dropzone.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css" integrity="sha512-Z/def5z5u2aR89OuzYcxmDJ0Bnd5V1cKqBEbvLOiUNWdg9PQeXVvXLI90SE4QOHGlfLqUnDNVAYyZi8UwUTmWQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
.dz-remove ,.dz-remove:hover{
  color:red;
  font-weight:bold;
}
.dropzone .dz-preview .dz-remove:hover {
  text-decoration: none !important;
}

<style>
.square {
  height: 50px;
  width: 50px;
  background-color: #555;
}

.tags-input {
            display: inline-block;
            position: relative;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
            /* box-shadow: 2px 2px 5px #00000033; */
            width: 100% !important;
        }
  
        .tags-input ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
  
        .tags-input li {
            display: inline-block;
            background-color: #f2f2f2;
            color: #333;
            border-radius: 20px;
            padding: 5px 10px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
  
        .tags-input input[type="text"] {
            border: 1px white solid !important;
            outline: none;
            padding: 5px;
            font-size: 14px;
            outline:none;
            border: none !important;
        }
  
        .tags-input input[type="text"]:focus {
            outline: none !important;
            border: 1px white solid !important;
        }
  
        .tags-input .delete-button {
            background-color: transparent;
            border: none;
            color: #999;
            cursor: pointer;
            margin-left: 5px;
        }
</style>
<div class="page-header">
            <h3 class="page-title">
            {{tran('Products')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"> {{tran('Products')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Products Edit')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
          <div class="row">
            <!----------------------------------------------------------------------->
            <form class="form form-horizontal mar-top" action="{{route('seller.products.update', $product)}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
            <input type="hidden" name="id" value="{{ $product->id }}">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Product Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="{{ tran('Product Name') }}" value="{{$product->name}}" onchange="update_sku()" required>
                            </div>
                           
                        </div>

                        <input type="hidden" name="added_by" value="seller">
                        
                        <div class="form-group row" id="category">
                            <label class="col-md-3 col-from-label">{{tran('Category')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" name="category_id" id="category_id" data-live-search="true" required>
                                <option value="">{{ tran('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"  <?php echo $category->id== $product-> category_id ? 'selected' : ''; ?>>{{ $category -> name }}</option>
                                   
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-md-3 col-from-label">{{tran('Brand')}}</label>
                            <div class="col-md-8">
                                <select class="form-control" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value="">{{ tran('Select Brand') }}</option>
                                    @foreach (\App\Models\Brand::active()->get() as $brand)
                                    <option value="{{ $brand->id }}"  <?php echo $brand->id== $product-> brand_id ? 'selected' : ''; ?>>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Unit')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="unit" value="{{$product->unit}}"  placeholder="{{ tran('Unit (e.g. KG, Pc etc)') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Weight')}} <small>({{ tran('In Kg') }})</small></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="weight" step="0.01" value="{{$product->weight }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Minimum Purchase Qty')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" lang="en" class="form-control" name="min_qty"  value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif"  min="1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Tags')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                            <div class="tags-input">
                              <ul id="tags"></ul>
                                <input type="text" class="form-control aiz-tag-input" style="width:90% !important"  id="input-tag" name="tags"  value="{{ $product->tags }}" placeholder="{{ tran('Type and hit enter to add a tag') }}">
                               <?php
                               $tags_arr=[];
                               if($product->tags !=""){
                                $tags_arr=explode(",",$product->tags);
                               }
                               ?>
                            </div>
                                <small class="text-muted">{{tran('This is used for search. Input those words by which cutomer can find this product.')}}</small>
                            </div>
                        </div>


                       
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Images')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{tran('Gallery Images')}} <small>(600x600)</small></label>
                            <div class="col-md-8">
                            <div class="form-group row">
                        <div id="dpz-multiple-files" class="dropzone dropzone-area col-12">
                        <div class="dz-message">{{tran('You can upload more than one photo here')}}</div>
                    </div>

                    <div class="file-preview box sm">
                        <br>
                      @if(count($product->images) >0)
                       @foreach($product->images as $img)
                       <a id="link<?php  echo explode('products/',$img->photo)[1];?>" onclick="removeinput('<?php  echo explode('products/',$img->photo)[1];?>')" style="margin-bottom:25px;cursor: pointer;position:absolute;background:url({{asset('assets/images/close.png')}}) no-repeat center center;width:30px;height:29px;"></a><img src="{{asset($img->photo)}}" height="150" width="150" id="img<?php  echo explode('products/',$img->photo)[1];?>" class="img-thumbnail">
                       <input type="hidden" name="document[]" id="<?php  echo explode('products/',$img->photo)[1];?>" value="<?php  echo explode('products/',$img->photo)[1];?>">
                       @endforeach
                        @endif
                                </div>

                    @error('document.*')
                        <span class="text-danger"> {{$message}}</span>
                    @enderror
                    <br>
                    <br>
                    </div>
                                <div class="file-preview box sm">
                                </div>
                                <small class="text-muted">{{tran('These images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{tran('Thumbnail Image')}} <small>(300x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <input type="file"  name="thumbnail_img"  value="{{ $product->thumbnail_img }}" style="height:100%"  accept="image/*" onchange="loadFile(event)" class="form-control form-control-sm" id="formFileSm">
                                    @error('thumbnail_img')
								<span class="text-danger">{{$message}}</span>
								@enderror
                                    <img id="output" src="{{asset('assets/images/products/'.$product->thumbnail_img ) }}" style="width:150px;height:150px;margin-left:10px;"/>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small class="text-muted">{{tran('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Variation')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{tran('Colors')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select class="select2 form-control"  data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors"  onchange="update_sku()" multiple <?php if (count(json_decode($product->colors)) == 0) echo "disabled"; ?> >
                                    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option  value="{{ $color->code }}"  <?php if (in_array($color->code, json_decode($product->colors))) echo 'selected' ?>><span>{{ $color->name }}</span></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active" <?php if (count(json_decode($product->colors)) > 0) echo "checked"; ?>>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{tran('Attributes')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select name="choice_attributes[]" id="choice_attributes" class="select2 form-control"  onchange="getattributes(this)" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="{{ tran('Choose Attributes') }}">
                                    @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                    <option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif >{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div>
                            <p>{{ tran('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">
                            @foreach (json_decode($product->choice_options) as $key => $choice_option)
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
                                    <input type="text" class="form-control" name="choice[]" value="{{ optional(\App\Models\Attribute::find($choice_option->attribute_id))->name}}" placeholder="{{ tran('Choice Title') }}" disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form-control select2 attribute_choice" data-live-search="true" name="choice_options_{{ $choice_option->attribute_id }}[]" multiple>
                                        @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get() as $row)
                                        <option value="{{ $row->value }}" @if( in_array($row->value, $choice_option->values)) selected @endif>
                                            {{ $row->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product price + stock')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Unit price')}} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="{{$product->unit_price}}" step="0.01" placeholder="{{ tran('Unit price') }}" name="unit_price" class="form-control" required>
                            </div>
                        </div>
                        @php
                          $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                          $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                         @endphp
                        <div class="form-group row">
	                        <label class="col-sm-3 control-label" for="start_date">{{tran('Discount Date Range')}}</label>
	                        <div class="col-sm-9">
                            <input type="hidden" class="form-control" id="date_discount" @if($product->discount_start_date && $product->discount_end_date) value="{{ $start_date.' - '.$end_date }}" @endif >

	                          <input type="text" class="form-control" name="date_range" @if($product->discount_start_date && $product->discount_end_date) value="{{ $start_date.' - '.$end_date }}" @endif   placeholder="{{tran('Select Date')}}">
	                        </div>
	                    </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Discount')}} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="{{$product->discount}}" step="0.01" placeholder="{{ tran('Discount') }}" name="discount" class="form-control" value="{{ $product->discount }}" required>
                            </div>
                            @error('discount')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            <div class="col-md-3">
                                <select class="form-control" name="discount_type">
                                <option value="amount" <?php if ($product->discount_type == 'amount') echo "selected"; ?> >{{tran('Flat')}}</option>
                                    <option value="percent" <?php if ($product->discount_type == 'percent') echo "selected"; ?> >{{tran('Percent')}}</option>
                                </select>
                            </div>
                        </div>

                        

                        <div id="show-hide-div">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{tran('Quantity')}} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="{{ optional($product->stocks->first())->qty }}"  step="1" placeholder="{{ tran('Quantity') }}" name="current_stock" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{tran('SKU')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ tran('SKU') }}" name="sku"  value="{{ optional($product->stocks->first())->sku }}"  class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{tran('External link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ tran('External link') }}" name="external_link" value="{{ $product->external_link }}"  class="form-control">
                                <small class="text-muted">{{tran('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{tran('External link button text')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ tran('External link button text') }}" name="external_link_btn" value="{{ $product->external_link_btn }}" class="form-control">
                                <small class="text-muted">{{tran('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <br>
                        <div class="sku_combination" id="sku_combination">

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Videos')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Video Provider')}}</label>
                            <div class="col-md-8">
                            <select class="form-control" name="video_provider" id="video_provider">
                                    <option value="youtube" <?php if ($product->video_provider == 'youtube') echo "selected"; ?> >{{tran('Youtube')}}</option>
                                 </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Video Link')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ tran('Video Link') }}">
                                <small class="text-muted">{{tran("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Description')}}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" id="editor" name="description">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

<!--                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Shipping Cost')}}</h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div>-->

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('PDF Specification')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{tran('PDF Specification')}}</label>
                            <div class="col-md-8">
                                    <input type="file"  value="" name="pdf" style="height:auto !important" class="form-control form-control-sm"  >
                                    @error('pdf')
								<span class="text-danger">{{$message}}</span>
								@enderror
                                </div>
                                @if($product->pdf !="")<a target="_blank" href="{{ asset('assets/images/products/'.$product->pdf )}}">{{tran('Show File')}}</a>@endif

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('SEO Meta Tags')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Meta Title')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"  value="{{ $product->meta_title }}" placeholder="{{ tran('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Description')}}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ tran('Meta Image') }}</label>
                            <div class="col-md-8">

                            <div class="input-group"  data-type="image">
                                    <input type="file" onchange="loadFile2(event)" name="meta_img" style="height:100% !important" class="form-control form-control-sm" >
                                    @error('meta_img')
								<span class="text-danger">{{$message}}</span>
								@enderror
                                    <img id="output2" style="width:150px;height:150px;margin-left:10px;" src="{{asset('assets/images/products/'.$product->meta_img) }}"/>
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{tran('Slug')}}</label>
                            <div class="col-md-8">
                                <input type="text" placeholder="{{tran('Slug')}}" id="slug" name="slug" value="{{ $product->slug }}" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{tran('Shipping Configuration')}}
                        </h5>
                    </div>

                    <div class="card-body">
                    @if (get_setting('shipping_type') == 'product_wise_shipping')
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Free Shipping')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="free"  @if($product->shipping_type == 'free') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Flat Rate')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="flat_rate_shipping_div" style="display: none">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{tran('Shipping cost')}}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0"  value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ tran('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Is Product Quantity Mulitiply')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1" @if($product->is_quantity_multiplied == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        @else
                        <p>
                           
                        </p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Low Stock Quantity Warning')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Quantity')}}
                            </label>
                            <input type="number" name="low_stock_quantity" value="{{ $product->low_stock_quantity }}"  min="0" step="1" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{tran('Stock Visibility State')}}
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Show Stock Quantity')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="quantity" @if($product->stock_visibility_state == 'quantity') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Show Stock With Text Only')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text" @if($product->stock_visibility_state == 'text') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Hide Stock')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide"  @if($product->stock_visibility_state == 'hide') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Cash On Delivery')}}</h5>
                    </div>
                    <div class="card-body">
                    @if (get_setting('cash_payment') == '1')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{tran('Status')}}</label>
                                <div class="col-md-6">
                                    <label class="chk-switch chk-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1"  @if($product->cash_on_delivery == 1) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            @else
                            <p>
                                {{ tran('Cash On Delivery option is disabled. Activate this feature from here') }}
                                <a href="" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{tran('Cash Payment Activation')}}</span>
                                </a>
                            </p>
                            @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Featured')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Status')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="checkbox" id="featured" value="1" class="setstatus" @if($product->featured == 1) checked @endif>
                                    
                                    <span></span>
                                </label>
                                <input type="hidden" name="featured" id="txtfeatured" value="{{$product->featured}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Trending')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Status')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="checkbox" id="trending" value="1" class="setstatus" @if($product->trending == 1) checked @endif>
                                    <span></span>
                                </label>
                                <input type="hidden" name="trending" id="txttrending" value="{{$product->trending}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Todays Deal')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Status')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="checkbox" id="todays_deal" value="1" class="setstatus" @if($product->todays_deal == 1) checked @endif>
                                    <span></span>
                                </label>
                                <input type="hidden" name="todays_deal" id="txttodays_deal" value="{{$product->todays_deal}}">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Flash Deal')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Add To Flash')}}
                            </label>
                            <select class="form-control" name="flash_deal_id" id="flash_deal">
                                <option value="">{{ tran('Choose Flash Title') }}</option>
                             
                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id}}" @if($product->flash_deal_product && $product->flash_deal_product->flash_deal_id == $flash_deal->id) selected @endif>
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                           
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Discount')}}
                            </label>
                            <input type="number" name="flash_discount"   value="{{ $product->discount }}"   min="0" step="0.01" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Discount Type')}}
                            </label>
                            <select class="form-control" name="flash_discount_type" id="flash_discount_type">
                                <option value="">{{ tran('Choose Discount Type') }}</option>
                                <option value="amount"  @if($product->discount_type == 'amount') selected @endif>{{tran('Flat')}}</option>
                                <option value="percent" @if($product->discount_type == 'percent') selected @endif>{{tran('Percent')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Estimate Shipping Time')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Shipping Days')}}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{tran('Shipping Days')}}" value="{{ $product->est_shipping_days }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend" style="height:78%">{{tran('Days')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('VAT & Tax')}}</h5>
                    </div>
                    <div class="card-body">
                        @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                        <label for="name">
                            {{$tax->name}}
                            <input type="hidden" value="{{$tax->id}}" name="tax_id[]">
                        </label>

                        @php
                        $tax_amount = 0;
                        $tax_type = '';
                        foreach($tax->product_taxes as $row) {
                            if($product->id == $row->product_id) {
                                $tax_amount = $row->tax;
                                $tax_type = $row->tax_type;
                            }
                        }
                        @endphp

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0"  value="{{ $tax_amount }}" step="0.01" placeholder="{{ tran('Tax') }}" name="tax[]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control" name="tax_type[]">
                                    <option value="amount" @if($tax_type == 'amount') selected @endif>{{tran('Flat')}}</option>
                                    <option value="percent" @if($tax_type == 'percent') selected @endif>{{tran('Percent')}}</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-12">
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-info" onclick="return gettags()" style="background-color:#392c70 !important;border:none;">{{ tran('Update Product') }}</button>
                </div>
            </div>
        </div>
    </form>
          </div>
            @push('js')
            <script>
                
    $(document).ready(function(){
        update_sku();

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

$(document).ready(function (){
        show_hide_shipping_div();
    });

    $("[name=shipping_type]").on("change", function (){
        show_hide_shipping_div();
    });

    function show_hide_shipping_div() {
        var shipping_val = $("[name=shipping_type]:checked").val();

        $(".flat_rate_shipping_div").hide();

        if(shipping_val == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    }
            </script>
              <script>
             var uploadedDocumentMap = {}
            Dropzone.options.dpzMultipleFiles = {
                paramName: "dzfile", // The name that will be used to transfer the file
                //autoProcessQueue: false,
                maxFilesize: 5, // MB
                clickable: true,
                addRemoveLinks: true,
                acceptedFiles: 'image/*',
                dictFallbackMessage: '<?php echo tran("Your browser does not support multi-image and drag-and-drop features")?>',
                dictInvalidFileType: '<?php echo tran("You cannot upload this type of file")?>',
                dictCancelUpload: '<?php echo tran("Cancel upload")?>',
                dictCancelUploadConfirmation: '<?php echo tran("Are you sure you want to cancel uploading files?")?>',
                dictRemoveFile: "x",
               
                dictMaxFilesExceeded: '<?php echo tran("You cannot upload more than this")?>',
                headers: {
                    'X-CSRF-TOKEN':
                        "{{ csrf_token() }}"
                }
                ,
                url: "{{ route('seller.products.images.store') }}", // Set the url
                success:
                    function (file, response) {
                        $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                        uploadedDocumentMap[file.name] = response.name
                    }
                ,
                removedfile: function (file) {
                    file.previewElement.remove()
                    var name = ''
                    if (typeof file.file_name !== 'undefined') {
                        name = file.file_name
                    } else {
                        name = uploadedDocumentMap[file.name]
                    }
                    $('form').find('input[name="document[]"][value="' + name + '"]').remove()
                }
                ,
                // previewsContainer: "#dpz-btn-select-files", // Define the container to display the previews
                init: function () {
                        @if(isset($event) && $event->document)
                    var files =
                    {!! json_encode($event->document) !!}
                        for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
                    }
                    @endif
                }
            }
            ///////////////////////////////////////////////////////////////////////////////
            function add_more_customer_choice_option(i, name){
                $.getScript("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js", function () {
            $.getScript("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js", function () { 
                $('.component').select2();
                 })
             })
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:"{{ route('seller.products.add-more-choice-option') }}",
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ tran('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control attribute_choice component" onchange="update_sku()" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
           
                //  AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

            $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });


    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });


    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group row').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

///////////////////////////
function getattributes(attribut_select) {
        // alert(attribut_select.value);
        $('#customer_choice_options').html(null);
        var opts = $('#choice_attributes option');
      for (var i = 0, n = opts.length; i < n; ++i) {
          if(opts[i].selected){
            $.getScript("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js", function () {
            $.getScript("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js", function () { 
                $('.component').select2();
            })})  
          add_more_customer_choice_option(opts[i].value, opts[i].text);
           }
        }
     update_sku();
     }

/////////////////////////
     function update_sku(){
       // alert("bbbbbb");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           type:"POST",
           url:"{{ route('seller.products.sku_combination_edit')}}",
           data:$('#choice_form').serialize(),
           success: function(data){
                $('#sku_combination').html(data);
                if (data.length > 1) {
                   
                    $('#show-hide-div').hide();
                }
                else {
                    
                    $('#show-hide-div').show();
                }
           }
        });
    }
</script>


<script>
      
      // Get the tags and input elements from the DOM
      const tags = document.getElementById('tags');
      const input = document.getElementById('input-tag');

      // Add an event listener for keydown on the input element
      input.addEventListener('keydown', function (event) {

          // Check if the key pressed is 'Enter'
          if (event.key === 'Enter') {
            
              // Prevent the default action of the keypress
              // event (submitting the form)
              event.preventDefault();
            
              // Create a new list item element for the tag
              const tag = document.createElement('li');
            
              // Get the trimmed value of the input element
              const tagContent = input.value.trim();
            
              // If the trimmed value is not an empty string
              if (tagContent !== '') {
            
                  // Set the text content of the tag to 
                  // the trimmed value
                  tag.innerText = tagContent;

                  // Add a delete button to the tag
                  tag.innerHTML += '<button class="delete-button">X</button>';
                    
                  // Append the tag to the tags list
                  tags.appendChild(tag);
                    
                  // Clear the input element's value
                  input.value = '';
              }
          }
      });

      // Add an event listener for click on the tags list
      tags.addEventListener('click', function (event) {

          // If the clicked element has the class 'delete-button'
          if (event.target.classList.contains('delete-button')) {
            
              // Remove the parent element (the tag)
              event.target.parentNode.remove();
          }
      });
  </script>
  <script>
    function gettags(){
        var lis = document.getElementById('tags').getElementsByTagName('li');
        var arr=[];
       for(var i=0;i<lis.length;i++){
        var litext=lis[i].innerText.replace(/X/g,' ').trim();
        arr.push(litext);
        console.log(litext);
       }
       console.log(arr);
       if(arr.length){
        document.getElementById('input-tag').value=arr;
       }
      return true;
    }
   </script>

<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display="";
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
<script>
  var loadFile2 = function(event) {
    var output = document.getElementById('output2');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display="";
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
<script>
//     function setstatus(ele){
//         var id=$(ele).id;
//    var txt=document.getElementById('txt'+id);
//    if ($(ele).is(':checked')) {
//     txt.value=1;
//    }else{
//     txt.value=0;
//    } 
//     }
    $(".setstatus").on('change', function(event){
   var id=this.id;
   var txt=document.getElementById('txt'+id);
   if ($(this).is(':checked')) {
    txt.value=1;
   }else{
    txt.value=0;
   }
});
</script>

<script>   
    var start_date='<?php echo $product->discount_start_date ; ?>';
    var end_date ='<?php echo  $product->discount_end_date   ;?>';
    // var autoUpdateInput_val=false;
    // if(start_date !='0' && end_date !='0'){
    //     autoUpdateInput_val=true;
    // }
        $('input[name="date_range"]').daterangepicker({
      timePicker: true,
    //  autoUpdateInput: autoUpdateInput_val,
     startDate: moment().startOf('hour'),
     endDate: moment().startOf('hour').add(32, 'hour'),
     locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });
    
  $('input[name="date_range"]').val($("#date_discount").val());
  $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
    $('input[name="date_range"]').val('');
  });
</script>
<script>
     var tagtxt=document.getElementById('input-tag');
     var tags_arr=[];
     if(tagtxt.value !=""){
        tags_arr=<?php echo json_encode($tags_arr);?>;
        if(tags_arr.length > 0){
            for(var i=0;i<tags_arr.length;i++){
                const tag = document.createElement('li');
            // Get the trimmed value of the input element
            const tagContent = tags_arr[i].trim();
            // If the trimmed value is not an empty string
            if (tagContent !== '') {
                // Set the text content of the tag to 
                // the trimmed value
                tag.innerText = tagContent;

                // Add a delete button to the tag
                tag.innerHTML += '<button class="delete-button">X</button>';
                  
                // Append the tag to the tags list
                tags.appendChild(tag);
                  
                // Clear the input element's value
             
              }
            }
            tagtxt.value = '';
        }
       
    }
</script>

<script>
   function removeinput(str) {
    var element = document.getElementById(str);
      element.remove();
      document.getElementById('img'+str).remove();
      document.getElementById('link'+str).remove();
   }
</script>
    @endpush
            @endsection