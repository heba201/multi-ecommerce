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

    
    <form  action="{{ route('payment.store_shipping_info') }}"  method="POST"  id="checkout-form">
    @csrf
   
    <main class="main" >
      <div class="section-box" >
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a>aa</li>
              <li><a class="font-xs color-gray-500" href="#">Shop</a></li>
              <li><a class="font-xs color-gray-500" href="#">Checkout</a></li>
            </ul>
          </div>
        </div>
      </div>
      <section class="section-box shop-template" >
        <div class="container" >
          <div class="row" >
            <div class="col-lg-12" style="margin:auto;">
              <div class="box-border">
                
              
              <div class="box-payment">
                <div class="col active"  style="padding:10px;">
                
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                           
                            <i class="fa-solid fa-map fa-3x"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('1. Shipping Informationt') }}
                              
                              </h3>
                            </div>
                        </div>
                        <div class="col"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
             
                <i class="fa-solid fa-truck-fast fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('2. Delivery Informationt') }}
                  
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
               
                <div class="row">
                @if(Auth::check())
                <div class="col-lg-12">
                    <h5 class="font-md-bold color-brand-3 mt-15 mb-20">Shipping address</h5>
                  </div>
                  @foreach (Auth::user()->addresses as $key => $address)
                <div class="col-lg-12">
                 
                <div class="listCheckout">
                  <div class="item-wishlist">
                    <div class="wishlist-product">
                      <div class="product-wishlist">
                  
                        <div class="product-info">
                        
                        <h6 class="color-brand-3">
                        <input type="radio" name="address_id" value="{{ $address->id }}" @if ($address->set_default)
                                                    checked
                                                @endif required>  
                        {{ $address->address }} 
                          
                        <br><br>
                        &nbsp;&nbsp;{{ optional($address->city)->name }} <br><br>
                        &nbsp;&nbsp;{{ optional($address->country)->name }}   <br><br>
                        &nbsp;&nbsp;{{ $address->postal_code }} <br><br>
                        &nbsp;&nbsp;{{ $address->phone}} <br><br>
                        </h6>
                          
                        </div>
                            <!-- Edit Address Button -->
                  <div class="col-md-4 p-3 text-right">
                                            <a class="btn btn-buy w-auto" onclick="edit_address('{{$address->id}}')">{{ tran('Change') }}</a>
                                        </div>
                      </div>
                     </div>
                  </div>
                </div>
            </div> 
                @endforeach
                @endif
               

                
                 <!-- Add New Address -->
            <div class="" onclick="add_new_address()">
                <div class="border p-3 mb-3 c-pointer text-center bg-light has-transition hov-bg-soft-light">
                    <i class="la la-plus la-2x"></i>
                    <div class="alpha-7 fs-14 fw-700">{{ tran('Add New Address') }}</div>
                </div>
            </div>
                </div>
              </div>
              <div class="row mt-20">
                <div class="col-lg-6 col-5 mb-20"><a class="btn font-sm-bold color-brand-1 arrow-back-1" href="shop-cart.html">{{tran('Return to Cart')}}</a></div>
                <div class="col-lg-6 col-7 mb-20 text-end"><button class="btn btn-buy w-auto arrow-next">{{tran('Save')}}</button></div>
              </div>
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

    @section('modal')
    <!-- Address Modal -->
    @include('modals.address_modal')
@endsection