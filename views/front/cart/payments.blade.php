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

    
    <form  action="{{ route('payment.checkout') }}"  method="POST"  id="checkout-form">
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
                <div class="col  done" style="padding:10px;">
                
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                          
                            <i class="fa-solid fa-map fa-3x"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('1. Shipping info') }}
                              
                              </h3>
                            </div>
                        </div>
                        <div class="col done"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                
                <i class="fa-solid fa-truck-fast fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('2. Delivery Information') }}
                  
                  </h3>
                </div>
            </div>
                 
            <div class="col active"  style="padding:10px;">
                
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

                <h5 class="font-md-bold mb-20">{{tran('Summary')}}</h5>
                 <!-- Items Count -->
            <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                {{ count($carts) }}
                {{ tran('Items') }}
            </span>
                <div class="listCheckout">
                @php
                $coupon_discount = 0;
            @endphp
            @if (Auth::check() && get_setting('coupon_system') == 1)
                @php
                    $coupon_code = null;
                @endphp
                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                    @endphp
                    @if ($cartItem->coupon_applied == 1)
                        @php
                            $coupon_code = $cartItem->coupon_code;
                            break;
                        @endphp
                    @endif
                @endforeach

                @php
                    $coupon_discount = carts_coupon_discount($coupon_code);
                @endphp
            @endif
            @php $subtotal_for_min_order_amount = 0; @endphp
            @foreach ($carts as $key => $cartItem)
                @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
            @endforeach

            @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                    {{ tran('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                </span>
            @endif

            <div class="card-body">
        <!-- Products Info -->
        <table class="table">
            <thead>
                <tr>
                    <th class="product-name border-top-0 border-bottom-1 pl-0 fs-12 fw-400 opacity-60">{{ tran('Product') }}</th>
                    <th class="product-total text-right border-top-0 border-bottom-1 pr-0 fs-12 fw-400 opacity-60">{{ tran('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = 0;
                    $shipping = 0;
                    $product_shipping_cost = 0;
                   
                @endphp
            
                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                        $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                        $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                        $pro_tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                        $product_shipping_cost = $cartItem['shipping_cost'];
                        $shipping += $product_shipping_cost;
                        $product_name_with_choice = $product->name;
                        if ($cartItem['variant'] != null) {
                          $product_name_with_choice = $product->name . ' - ' . $cartItem['variant'];
                        }
                    @endphp
                    <tr class="cart_item">
                        <td class="product-name pl-0 fs-14 text-dark fw-400 border-top-0 border-bottom">
                            {{ $product_name_with_choice }}
                            <strong class="product-quantity">
                                × {{ $cartItem['quantity'] }}
                            </strong>
                        </td>
                        <?php
                  
                        ?>
                        <td class="product-total text-right pr-0 fs-14 text-primary fw-600 border-top-0 border-bottom">
                            <span
                                class="pl-4 pr-0">{{ single_price(cart_product_price($cartItem, $cartItem->product, false, false))}}</span>
                       <br>
                       
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <input type="hidden" id="sub_total" value="{{ $subtotal }}">

        <table class="table" style="margin-top: 2rem!important;">
            <tfoot>
                <!-- Subtotal -->
                <tr class="cart-subtotal">
                    <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ tran('Subtotal') }}</th>
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{single_price($subtotal)}}</span>
                    </td>
                </tr>
                <!-- Tax -->
                <tr class="cart-shipping">
                    <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ tran('Tax') }}</th>
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{single_price($tax)}}</span>
                    </td>
                </tr>
                <!-- Total Shipping -->
                <tr class="cart-shipping">
                    <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ tran('Total Shipping') }}</th>
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{ single_price($shipping) }}</span>
                    </td>
                </tr>
                <!-- Redeem point -->
                @if (Session::has('club_point'))
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ tran('Redeem point') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                            <span class="fw-600">{{ single_price(Session::get('club_point')) }}</span>
                        </td>
                    </tr>
                @endif
                <!-- Coupon Discount -->
                @if ($coupon_discount > 0)
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ tran('Coupon Discount') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                            <span class="fw-600">{{ single_price($coupon_discount) }}</span>
                        </td>
                    </tr>
                @endif

                @php
                    $total = $subtotal + $tax + $shipping;
                    if (Session::has('club_point')) {
                        $total -= Session::get('club_point');
                    }
                    if ($coupon_discount > 0) {
                        $total -= $coupon_discount;
                    }
                @endphp
                <!-- Total -->
                <tr class="cart-total">
                    <th class="pl-0 fs-14 text-dark fw-600"><span class="strong-600">{{ tran('Total') }}</span></th>
                    <td class="text-right pr-0 fs-14 fw-600 text-primary">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>

      

        <!-- Coupon System -->
        @if (Auth::check() && get_setting('coupon_system') == 1)
            @if ($coupon_discount > 0 && $coupon_code)
                <div class="mt-3">
                    <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ $coupon_code }}</div>
                            <div class="input-group-append">
                                <button type="button" id="coupon-remove"
                                    class="btn btn-primary">{{ tran('Change Coupon') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-3">
                    <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                        <div class="input-group">
                    <div class="form-group d-flex mt-15">
                  <input class="form-control mr-15" placeholder="{{ tran('Have coupon code? Apply here') }}" name="code" id="code"  onkeydown="return event.key != 'Enter';" >
                  <button class="btn btn-buy w-auto" id="coupon-apply">{{tran('Apply')}}</button>
                </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif
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
                                                   
                                                </div>

                                               
                                            </div>
                                        </div>

                                       
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
                                        
                                        </div>
                                    </div>
                                @endforeach
                            @endif
              </div>
              
            
            </div>
              </div>

            
<!-- select payment !-->
<div class="container" style="margin-top:15px !important;">
          
            <div class="row box-border">
            <div class="card-header p-4 border-bottom-0">
            <h3 class="fs-16 fw-700 text-dark mb-0">
                {{ tran('Select a payment option')}}
            </h3>
        </div>
          <!-- Cash Payment -->
          @if (get_setting('cash_payment') == 1)
                                        @php
                                            $digital = 0;
                                            $cod_on = 1;
                                            foreach ($carts as $cartItem) {
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                if ($product['digital'] == 1) {
                                                    $digital = 1;
                                                }
                                                if ($product['cash_on_delivery'] == 0) {
                                                    $cod_on = 0;
                                                }
                                            }
                                        @endphp
                                        @if ($digital != 1 && $cod_on == 1)
                                            <div class="col-6 col-xl-3 col-md-4" style="margin-top:15px  !important;">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="cash_on_delivery" class="online_payment"
                                                        type="radio" name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ asset('assets/img/cards/cod.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ tran('Cash on Delivery') }}</span>
                                                        </span>
                                                    </span>
                                                </label>

                                            </div>
                                        @endif
                                    @endif
                                  

                                       <!-- Paypal -->
                                       @if (get_setting('paypal_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4" style="margin-top:15px  !important;">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paypal" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ asset('assets/img/cards/paypal.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ tran('Paypal') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                      <!--Stripe -->
                                      @if (get_setting('stripe_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4" style="margin-top:15px  !important;">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="stripe" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ asset('assets/img/cards/stripe.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ tran('Stripe') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                 </div>
                                </div>
                                </div>


            
                                </div>
                                <div class="row mt-20">
                <div class="col-lg-6 col-5 mb-20"><a class="btn font-sm-bold color-brand-1 arrow-back-1" href="{{route('site.cart.index')}}">{{tran('Return to Cart')}}</a></div>
                <div class="col-lg-6 col-7 mb-20 text-end"><button class="btn btn-buy w-auto arrow-next"  onclick="submitOrder(this)">Place an Order</button></div>
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
        $(document).on("click", "#coupon-apply", function() {
          //  var data = new FormData($('#apply-coupon-form')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: "{{ route('payment.applyCoupon') }}",
                data: {
                  'code':$("#code").val()
                },
                success: function(data, textStatus, jqXHR) {
                   console.log(data);
                   swal({
            text: data.message,  
            icon: data.response,
          });
                }
            });
            return false;
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('payment.remove_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                 console.log(data);
                }
            })
          
        });



        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};
        function submitOrder(el) {
            $(el).prop('disabled', true);
            if ($('#agree_checkbox').is(":checked")) {
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    
                } else {
                  
                        $('#checkout-form').submit();
                }
            } else {
                
                $(el).prop('disabled', false);
            }
        }

        </script>
    @endpush