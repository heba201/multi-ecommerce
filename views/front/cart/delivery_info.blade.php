@extends('layouts.site')

@section('style')

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:weight@100;200;300;400;500;600;700;800&display=swap");

        body {
            background-color: #f5eee7;
            font-family: "Poppins", sans-serif;
            font-weight: 300
        }

        .container {
            height: 100vh
        }

        .card {
            border: none
        }

        .card-header {
            padding: .5rem 1rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .03);
            border-bottom: none
        }

        .btn-light:focus {
            color: #212529;
            background-color: #e2e6ea;
            border-color: #dae0e5;
            box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, .5)
        }

        .form-control {
            height: 50px;
            border: 2px solid #eee;
            border-radius: 6px;
            font-size: 14px
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #039be5;
            outline: 0;
            box-shadow: none
        }

        .input {
            position: relative
        }

        .input i {
            position: absolute;
            top: 16px;
            left: 11px;
            color: #989898
        }

        .input input {
            text-indent: 25px
        }

        .card-text {
            font-size: 13px;
            margin-left: 6px
        }

        .certificate-text {
            font-size: 12px
        }

        .billing {
            font-size: 11px
        }

        .super-price {
            top: 0px;
            font-size: 22px
        }

        .super-month {
            font-size: 11px
        }

        .line {
            color: #bfbdbd
        }

        .free-button {
            background: #1565c0;
            height: 52px;
            font-size: 15px;
            border-radius: 8px
        }

        .payment-card-body {
            flex: 1 1 auto;
            padding: 24px 1rem !important
        }

    </style>
    @stop

@section('content')

@php
                                $admin_products = array();
                                $seller_products = array();
                                $admin_product_variation = array();
                                $seller_product_variation = array();
                                foreach ($carts as $key => $cartItem){
                                    $product = \App\Models\Product::find($cartItem['product_id']);

                                    if($product->added_by == 'admin'){
                                        array_push($admin_products, $cartItem['product_id']);
                                        $admin_product_variation[] = $cartItem['variation'];
                                    }
                                    else{
                                        $product_ids = array();
                                        if(isset($seller_products[$product->user_id])){
                                            $product_ids = $seller_products[$product->user_id];
                                        }
                                        array_push($product_ids, $cartItem['product_id']);
                                        $seller_products[$product->user_id] = $product_ids;
                                        $seller_product_variation[] = $cartItem['variation'];
                                    }
                                }
                                
                                $pickup_point_list = array();
                                if (get_setting('pickup_point') == 1) {
                                    $pickup_point_list = \App\Models\PickupPoint::where('pick_up_status',1)->get();
                                }
                            @endphp

   
    <form  action="{{ route('payment.store_delivery_info') }}"  method="POST" >
    @csrf
     <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
    <main class="main">
      <div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="shop-grid.html">{{tran('Shop')}}</a></li>
              <li><a class="font-xs color-gray-500" href="shop-cart.html">{{tran('Checkout')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
      <section class="section-box shop-template">
        <div class="container">
          <div class="row"> 
            <div class="col-lg-12">
            <div class="box-border">
            <div class="box-payment">
                <div class="col done" style="padding:10px;">
                
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                          
                            <i class="fa-solid fa-map fa-3x"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block active">{{ tran('1. Shipping info') }}
                              
                              </h3>
                            </div>
                        </div>
                        <div class="col active"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                
                <i class="fa-solid fa-truck-fast fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('2. Delivery Information') }}
                  
                  </h3>
                </div>
            </div>
                 
            <div class="col"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                <i class="fa-solid fa-credit-card fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('3. Payment') }}
                  
                  </h3>
                </div>
            </div>

            <div class="col"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                <i class="fa-solid fa-circle-check fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('3. Confirmation') }}
                  
                  </h3>
                </div>
            </div>

                </div>

                <div class="listCheckout">
               

            <div class="card-body">

          </div>
          </div>
              </div>

        <!--  delivery info !-->
              <div class="row" style="margin-top:15px;">
            <div class="col-lg-12">
            <div class="box-border">
                <!-- Inhouse Products -->
                @if (!empty($admin_products))
                            <div class="card mb-5 border-0 rounded-0 shadow-none">
                                <div class="card-header py-3 px-0 border-bottom-0">
                                    <h5 class="fs-16 fw-700 text-dark mb-0">{{ get_setting('site_name') }} {{ tran('Inhouse Products') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Product List -->
                                    <ul class="list-group list-group-flush border p-3 mb-3">
                                        @php
                                            $physical = false;
                                        @endphp
                                        @foreach ($admin_products as $key => $cartItem)
                                            @php
                                                $product = \App\Models\Product::find($cartItem);
                                                if ($product->digital == 0) {
                                                    $physical = true;
                                                }
                                            @endphp
                                            <li class="list-group-item">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 mr-md-3">
                                                        <img src="{{ asset('assets/images/products/'.$product->thumbnail_img) }}"
                                                            class="img-fit size-60px"
                                                            alt="{{  $product->name  }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </span>
                                                    <span class="fs-14 fw-400 text-dark">&nbsp;
                                                        {{ $product->name }}
                                                        <br>
                                                        @if ($admin_product_variation[$key] != '')
                                                        &nbsp;    <span class="fs-12 text-secondary">{{ tran('Variation') }}: {{ $admin_product_variation[$key] }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <!-- Choose Delivery Type -->
                                    @if ($physical)
                                        <div class="row pt-3">
                                            <div class="col-md-6">
                                                <h6 class="fs-14 fw-700 mt-3">{{ tran('Choose Delivery Type') }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row gutters-5">
                                                    <!-- Home Delivery -->
                                                    @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="shipping_type_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                                value="home_delivery"
                                                                onchange="show_pickup_point(this, 'admin')"
                                                                data-target=".pickup_point_id_admin"
                                                                checked
                                                            >
                                                            <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{  tran('Home Delivery') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <!-- Carrier -->
                                                    @else
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="shipping_type_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                                value="carrier"
                                                                onchange="show_pickup_point(this, 'admin')"
                                                                data-target=".pickup_point_id_admin"
                                                                checked
                                                            >
                                                            <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{  tran('Carrier') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    @endif
                                                    <!-- Local Pickup -->
                                                    @if ($pickup_point_list)
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="shipping_type_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                                value="pickup_point"
                                                                onchange="show_pickup_point(this, 'admin')"
                                                                data-target=".pickup_point_id_admin"
                                                            >
                                                            <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{ tran('Local Pickup') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    @endif
                                                </div>

                                                <!-- Pickup Point List -->
                                                @if ($pickup_point_list)
                                                    <div class="mt-3 pickup_point_id_admin d-none">
                                                        <select
                                                            class="form-control rounded-0"
                                                            name="pickup_point_id_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                            data-live-search="true"
                                                        >
                                                                <option>{{tran('Select your nearest pickup point')}}</option>
                                                            @foreach ($pickup_point_list as $pick_up_point)
                                                                <option
                                                                    value="{{ $pick_up_point->id }}"
                                                                    data-content="<span class='d-block'>
                                                                                    <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->name }}</span>
                                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->address }}</span>
                                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                                </span>"
                                                                >{{ tran('Address') .':'. $pick_up_point->address .'/'. tran('Phone') .':'. $pick_up_point->phone .'/' .$pick_up_point->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Carrier Wise Shipping -->
                                        @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                            <div class="row pt-3 carrier_id_admin">
                                                @foreach($carrier_list as $carrier_key => $carrier)
                                                    <div class="col-md-12 mb-2">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="carrier_id_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                                value="{{ $carrier->id }}"
                                                                @if($carrier_key == 0) checked @endif
                                                            >
                                                            <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>&nbsp;
                                                                <span class="flex-grow-1 pl-3 fw-600">
                                                                    <img src="{{ $carrier->logo !='' ? asset('assets/images/carriers/'.$carrier->logo) : asset('assets/images/default.png')}}" alt="Image" class="w-50px img-fit">
                                                                </span>
                                                                <span class="flex-grow-1 pl-3 fw-700">{{ $carrier->name }}</span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{ tran('Transit in').' '.$carrier->transit_time }}</span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, \App\Models\User::where('user_type', 'admin')->first()->id)) }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Seller Products -->
                            @if (!empty($seller_products))
                                @foreach ($seller_products as $key => $seller_product)
                                    <div class="card mb-5 border-0 rounded-0 shadow-none">
                                        <div class="card-header py-3 px-0 border-bottom-0">
                                            <h5 class="fs-16 fw-700 text-dark mb-0">{{ \App\Models\Shop::where('user_id', $key)->first()->name }} {{ tran('Products') }}</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <!-- Product List -->
                                            <ul class="list-group list-group-flush border p-3 mb-3">
                                                @php
                                                    $physical = false;
                                                @endphp
                                                @foreach ($seller_product as $key2 => $cartItem)
                                                    @php
                                                        $product = \App\Models\Product::find($cartItem);
                                                        if ($product->digital == 0) {
                                                            $physical = true;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mr-2 mr-md-3">
                                                                <img src="{{ asset('assets/images/products/'.$product->thumbnail_img) }}"
                                                                    class="img-fit size-60px"
                                                                    alt="{{  $product->name  }}"
                                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            </span>
                                                           &nbsp; <span class="fs-14 fw-400 text-dark">
                                                                {{ $product->name }}
                                                                <br>
                                                                @if ($seller_product_variation[$key2] != '')
                                                                &nbsp;   <span class="fs-12 text-secondary">{{ tran('Variation') }}: {{ $seller_product_variation[$key2] }}</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <!-- Choose Delivery Type -->
                                            @if ($physical)
                                                <div class="row pt-3">
                                                    <div class="col-md-6">
                                                        <h6 class="fs-14 fw-700 mt-3">{{ tran('Choose Delivery Type') }}</h6>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gutters-5">
                                                            <!-- Home Delivery -->
                                                            @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                            <div class="col-6">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input
                                                                        type="radio"
                                                                        name="shipping_type_{{ $key }}"
                                                                        value="home_delivery"
                                                                        onchange="show_pickup_point(this, {{ $key }})"
                                                                        data-target=".pickup_point_id_{{ $key }}"
                                                                        checked
                                                                    >
                                                                    <span class="d-flex p-3 aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">{{  tran('Home Delivery') }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <!-- Carrier -->
                                                            @else
                                                            <div class="col-6">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input
                                                                        type="radio"
                                                                        name="shipping_type_{{ $key }}"
                                                                        value="carrier"
                                                                        onchange="show_pickup_point(this, {{ $key }})"
                                                                        data-target=".pickup_point_id_{{ $key }}"
                                                                        checked
                                                                    >
                                                                    <span class="d-flex p-3 aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">{{  tran('Carrier') }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            @endif
                                                            <!-- Local Pickup -->
                                                            @if ($pickup_point_list)
                                                                <div class="col-6">
                                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                                        <input
                                                                            type="radio"
                                                                            name="shipping_type_{{ $key }}"
                                                                            value="pickup_point"
                                                                            onchange="show_pickup_point(this, {{ $key }})"
                                                                            data-target=".pickup_point_id_{{ $key }}"
                                                                        >
                                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                            <span class="flex-grow-1 pl-3 fw-600">{{  tran('Local Pickup') }}</span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Pickup Point List -->
                                                        @if ($pickup_point_list)
                                                            <div class="mt-4 pickup_point_id_{{ $key }} d-none">
                                                                <select
                                                                    class="form-control aiz-selectpicker rounded-0"
                                                                    name="pickup_point_id_{{ $key }}"
                                                                    data-live-search="true"
                                                                >
                                                                    <option>{{ tran('Select your nearest pickup point')}}</option>
                                                                        @foreach ($pickup_point_list as $pick_up_point)
                                                                        <option
                                                                            value="{{ $pick_up_point->id }}"
                                                                            data-content="<span class='d-block'>
                                                                                            <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->name }}</span>
                                                                                            <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->address }}</span>
                                                                                            <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                                        </span>"
                                                                        >{{ tran('Address') .':'. $pick_up_point->address .'/'. tran('Phone') .':'. $pick_up_point->phone .'/' .$pick_up_point->name }}
                                                                        </option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Carrier Wise Shipping -->
                                                @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                                    <div class="row pt-3 carrier_id_{{ $key }}">
                                                        @foreach($carrier_list as $carrier_key => $carrier)
                                                            <div class="col-md-12 mb-2">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input
                                                                        type="radio"
                                                                        name="carrier_id_{{ $key }}"
                                                                        value="{{ $carrier->id }}"
                                                                        @if($carrier_key == 0) checked @endif
                                                                    >
                                                                    <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>&nbsp;
                                                                        <span class="flex-grow-1 pl-3 fw-600">
                                                                            <img src="{{ $carrier->logo !='' ? asset('assets/images/carriers/'.$carrier->logo) : asset('assets/images/default.png')}}" alt="Image" class="w-50px img-fit">
                                                                        </span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">{{ $carrier->name }}</span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">{{ tran('Transit in').' '.$carrier->transit_time }}</span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, $key))  }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
              </div>
              
            
            </div>
              </div>

  
             </div>
              </div>

              <div class="row mt-20">
                <div class="col-lg-6 col-5 mb-20"><a class="btn font-sm-bold color-brand-1 arrow-back-1" href="{{route('site.cart.index')}}">{{tran('Return to Cart')}}</a></div>
                <div class="col-lg-6 col-7 mb-20 text-end"><button class="btn btn-buy w-auto arrow-next"  onclick="submitOrder(this)">{{tran('Continue to payment')}}</button></div>
              </div>
            
                                </div>
                     

                                </div>
                   
                                </div>
               
                               
          
        </div>
      </section>
   
      
    </main>
   </form>
    @endsection

@push('js')
<script>
function show_pickup_point(el,type) {
        	var value = $(el).val();
        	var target = $(el).data('target');

        	if(value == 'home_delivery' || value == 'carrier'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
                $('.carrier_id_'+type).removeClass('d-none');
        	}else{
        		$(target).removeClass('d-none');
        		$('.carrier_id_'+type).addClass('d-none');
        	}
        }
        </script>
    @endpush