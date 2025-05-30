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
                <li class="breadcrumb-item"><a href="#">{{tran('Products')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Add New Product')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
          <div class="row">
            <!----------------------------------------------------------------------->
            <form class="form form-horizontal mar-top" action="{{route('seller.products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{tran('Product Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Product Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="{{ tran('Product Name') }}" onchange="update_sku()" required>
                            </div>
                           
                        </div>

                        <input type="hidden" name="added_by" value="seller">
                        
                        <div class="form-group row" id="category">
                            <label class="col-md-3 col-from-label">{{tran('Category')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>
                                <option value="">{{ tran('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category -> name }}</option>
                                    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-md-3 col-from-label">{{tran('Brand')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value="">{{ tran('Select Brand') }}</option>
                                    @foreach (\App\Models\Brand::active()->get() as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Unit')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="unit" placeholder="{{ tran('Unit (e.g. KG, Pc etc)') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Weight')}} <small>({{ tran('In Kg') }})</small></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="weight" step="0.01" value="0.00" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Minimum Purchase Qty')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Tags')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                            <div class="tags-input">
                              <ul id="tags"></ul>
                                <input type="text" class="form-control aiz-tag-input" style="width:90% !important"  id="input-tag" name="tags" placeholder="{{ tran('Type and hit enter to add a tag') }}">
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
                                    <input type="file"  name="thumbnail_img"   style="height:100%"  accept="image/*" onchange="loadFile(event)" class="form-control form-control-sm" id="formFileSm">
                                    @error('thumbnail_img')
								<span class="text-danger">{{$message}}</span>
								@enderror
                                    <img id="output" style="width:150px;height:150px;display:none;margin-left:10px;"/>
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
                                <select class="select2 form-control"  data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors"  onchange="update_sku()" multiple disabled>
                                    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option  value="{{ $color->code }}" ><span>{{ $color->name }}</span></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active">
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
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div>
                            <p>{{ tran('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">

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
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ tran('Unit price') }}" name="unit_price" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
	                        <label class="col-sm-3 control-label" for="start_date">{{tran('Discount Date Range')}}</label>
	                        <div class="col-sm-9">
                              <input type="text" class="form-control" name="date_range" placeholder="{{tran('Select Date')}}">
                            </div>
	                    </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Discount')}} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ tran('Discount') }}" name="discount" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control aiz-selectpicker" name="discount_type">
                                    <option value="amount">{{tran('Flat')}}</option>
                                    <option value="percent">{{tran('Percent')}}</option>
                                </select>
                            </div>
                        </div>

                        

                        <div id="show-hide-div">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{tran('Quantity')}} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ tran('Quantity') }}" name="current_stock" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{tran('SKU')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ tran('SKU') }}" name="sku" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{tran('External link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ tran('External link') }}" name="external_link" class="form-control">
                                <small class="text-muted">{{tran('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{tran('External link button text')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ tran('External link button text') }}" name="external_link_btn" class="form-control">
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
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube">{{tran('Youtube')}}</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Video Link')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link" placeholder="{{ tran('Video Link') }}">
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
                                <textarea class="aiz-text-editor" id="editor" name="description"></textarea>
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
                                    <input type="file"  name="pdf" style="height:auto !important" class="form-control form-control-sm"  >
                                    @error('pdf')
								<span class="text-danger">{{$message}}</span>
								@enderror
                                </div>
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
                                <input type="text" class="form-control" name="meta_title" placeholder="{{ tran('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{tran('Description')}}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control"></textarea>
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
                                    <img id="output2" style="width:150px;height:150px;display:none;margin-left:10px;"/>
                            </div>
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
                                    <input type="radio" name="shipping_type" value="free" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Flat Rate')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="flat_rate">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="flat_rate_shipping_div" style="display: none">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{tran('Shipping cost')}}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ tran('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Is Product Quantity Mulitiply')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1">
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
                            <input type="number" name="low_stock_quantity" value="1" min="0" step="1" class="form-control">
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
                                    <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Show Stock With Text Only')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{tran('Hide Stock')}}</label>
                            <div class="col-md-6">
                                <label class="chk-switch chk-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide">
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
                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">
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
                                    <input type="checkbox" id="featured" value="1" class="setstatus">
                                    
                                    <span></span>
                                </label>
                                <input type="hidden" name="featured" id="txtfeatured" value="0">
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
                                    <input type="checkbox" id="trending" value="1" class="setstatus">
                                    <span></span>
                                </label>
                                <input type="hidden" name="trending" id="txttrending" value="0">
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
                                    <input type="checkbox" id="todays_deal" value="1" class="setstatus">
                                    <span></span>
                                </label>
                                <input type="hidden" name="todays_deal" id="txttodays_deal" value="0">

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
                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">
                                <option value="">{{ tran('Choose Flash Title') }}</option>
                             
                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id}}">
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                           
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Discount')}}
                            </label>
                            <input type="number" name="flash_discount" value="0" min="0" step="0.01" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{tran('Discount Type')}}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="flash_discount_type">
                                <option value="">{{ tran('Choose Discount Type') }}</option>
                                <option value="amount">{{tran('Flat')}}</option>
                                <option value="percent">{{tran('Percent')}}</option>
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
                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{tran('Shipping Days')}}">
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

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ tran('Tax') }}" name="tax[]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control aiz-selectpicker" name="tax_type[]">
                                    <option value="amount">{{tran('Flat')}}</option>
                                    <option value="percent">{{tran('Percent')}}</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="Third group">
                        <button type="submit" name="button" value="unpublish" class="btn btn-primary action-btn" style="left:10px;">{{ tran('Save & Unpublish') }}</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Second group"> 
                        <button type="submit" name="button" value="publish" onclick="return gettags()" class="btn btn-success action-btn">{{ tran('Save & Publish') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
          </div>
          @endsection
            @push('js')
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
                dictCancelUpload:'<?php echo tran("Cancel upload")?>',
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
    // update_sku();
    //add_more_customer_choice_option(attribut_select.options[attribut_select.selectedIndex].value,attribut_select.options[attribut_select.selectedIndex].text);
     }


    function update_sku(){
        $.ajax({
           type:"POST",
           url:"{{ route('seller.products.sku_combination') }}",
           data:$('#choice_form').serialize(),
           success: function(data) {
            // console.log(data);
                $('#sku_combination').html(data);
                // AIZ.uploader.previewGenerate();
                // AIZ.plugins.fooTable();
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
        $('input[name="date_range"]').daterangepicker({
      timePicker: true,
    //  autoUpdateInput: autoUpdateInput_val,
     startDate: moment().startOf('hour'),
     endDate: moment().startOf('hour').add(32, 'hour'),
     locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });
  $('input[name="date_range"]').val('');

  $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
    $('input[name="date_range"]').val('');
  });
</script>
    @endpush