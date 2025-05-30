@extends('layouts.site')

@section('content')

<section class="section-box bg-home9" style="padding-top:25px !important;"> 
          <div class="container"> 
          <div class="row"> 
            <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                    {{tran('Coupons')}}
                   </span>
               
                </h3>
               @if($coupons->count() > 0)
                @foreach ($coupons->take(10) as $key => $coupon)
                @if($coupon->user->user_type == 'admin' || ($coupon->user->shop != null && $coupon->user->shop->verification_status))
                @if($coupon->type == 'product_base')
                        @php
                            $products = json_decode($coupon->details); 
                            $coupon_products = [];
                            foreach($products as $product) {                            
                                array_push($coupon_products, $product->product_id);                           
                            }
                        @endphp
                    @else                 
                        @php 
                            $order_discount = json_decode($coupon->details); 
                        @endphp             
                    @endif
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
              <div class="box-slider-product"> 
                <div class="head-slider"> 
                  <div class="row"> 
                    <div class="col-lg-7"> 
             
                            @php 
                if($coupon->user->user_type != 'admin') {
                    $shop = $coupon->user->shop;
                    $name = $shop->name;
                }
                else {
                    $name = get_setting('website_name');
                }
                @endphp
                      <h5>{{$name}}</h5>
                      
                      @if($coupon->discount_type == 'amount')
                <p class="fs-16 fw-500 mb-1">{{ single_price($coupon->discount) }} {{ tran('OFF') }}</p>    
            @else
                <p class="fs-16 fw-500 mb-1">{{ $coupon->discount }}% {{ tran('OFF') }}</p>    
            @endif

               <!-- Coupon Code -->
       
               <p><span class="fs-13 d-block">
                {{ tran('Code') }}:
                <span class="fw-600">{{ $coupon->code}}</span>
                <span class="ml-2 fs-16" style="cursor:pointer;" onclick="copyCouponCode('{{ $coupon->code }}')" data-toggle="tooltip" data-title="{{ tran('Copy the Code') }}" data-placement="top"><i class="fa fa-copy"></i></span>
            </span></p>
                    </div>
                    <div class="col-lg-5"> 
                      <div class="box-button-slider-2">
                      @if($coupon->type == 'product_base')
                        <div class="swiper-button-prev swiper-button-prev-style-top swiper-button-prev-newarrival"></div>
                        <div class="swiper-button-next swiper-button-next-style-top swiper-button-next-newarrival"></div>
                        @endif
                    </div>
                    </div>
                  </div>
                </div>
                <div class="content-products"> 
                  <div class="box-swiper">
                    <div class="swiper-container swiper-group-3-newarrival">
                      <div class="swiper-wrapper">

                     
                        @if($coupon->type == 'product_base')
                        <!-- Coupon Products -->
                        @php $products_el = App\Models\Product::whereIn('id', $coupon_products)->get(); @endphp
                        @foreach($products_el as $product) 
                        @include('front.includes.coupon_box',['coupon' => $coupon])
                        @endforeach

                        @else
                <!-- Coupon Discount range -->
                <span class="fs-12  pb-lg-3 d-block m-auto ">
                    @if($coupon->discount_type == 'amount')
                        {{ tran('Min Spend ') }} <strong>{{ single_price($order_discount->min_buy) }}</strong> {{ tran('from') }} <strong>{{ $name }}</strong> {{ tran('to get') }} <strong>{{ single_price($coupon->discount) }}</strong> {{ tran('OFF on total orders') }}
                    @else 
                        {{ tran('Min Spend ') }} <strong>{{ single_price($order_discount->min_buy) }}</strong> {{ tran('from') }} <strong>{{ $name }}</strong> {{ tran('to get') }} <strong>{{ $coupon->discount }}%</strong> {{ tran('OFF on total orders') }}                                   
                    @endif
                </span>
            @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
             
            </div>
            @endif
            @endforeach
            @endif
            </div>
        </div>
      </section>

@endsection