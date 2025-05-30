@extends('layouts.site')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')
<style>
    .banner-hero .box-swiper {
    height: 400px !important;
}
</style>
<div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="{{ route('shop.visit', $shop->slug) }}">{{$shop->name}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    <section class="mt-3 mb-3 bg-white">
        <div class="container">
            <!--  Top Menu -->
            <div class="d-flex flex-wrap justify-content-center justify-content-md-start" >
                <a style="padding:15px !important;" class="fw-700 fs-11 fs-md-13 mr-3 mr-sm-4 mr-md-5 text-dark opacity-60 hov-opacity-100 @if(!isset($type)) opacity-100 @endif"
                        href="{{ route('shop.visit', $shop->slug) }}">{{ tran('Store Home')}}</a>&nbsp;
                <a style="padding:15px !important;" class="fw-700 fs-11 fs-md-13 mr-3 mr-sm-4 mr-md-5 text-dark opacity-60 hov-opacity-100 @if(isset($type) && $type == 'top-selling') opacity-100 @endif" 
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'top-selling']) }}">{{ tran('Top Selling')}}</a>&nbsp;
                <a style="padding:15px !important;" class="fw-700 fs-11 fs-md-13 mr-3 mr-sm-4 mr-md-5 text-dark opacity-60 hov-opacity-100 @if(isset($type) && $type == 'cupons') opacity-100 @endif" 
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'cupons']) }}">{{ tran('Coupons')}}</a>&nbsp;
                <a style="padding:15px !important;" class="fw-700 fs-11 fs-md-13 text-dark opacity-60 hov-opacity-100 @if(isset($type) && $type == 'all-products') opacity-100 @endif" 
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all-products']) }}">{{ tran('All Products')}}</a>&nbsp;
            </div>
        </div>
    </section>

    <section  class="section-box" style="background: #fcfcfd;">
        <div class="container">

        @if (!isset($type) || $type == 'top-selling' || $type == 'cupons')
        @if ($shop->top_banner)
            <!-- Top Banner -->
            <section class="mt-3 mb-3">
                <img class="d-block lazyload h-100 img-fit" 
                    
                src="{{ asset('assets/images/shops/'.$shop->top_banner) }}" alt="{{ env('APP_NAME') }} offer">
            </section>
        @endif
    @endif
            <!-- Seller Info -->
            <div class="py-4">
                <div class="row justify-content-md-between align-items-center">
                  
                
                <div class="col-lg-5 col-md-6">
                        <div class="d-flex align-items-center">
                            <!-- Shop Logo -->
                            <?php
                            if($shop->logo !=""){
                              $logo=asset('assets/images/shops/'.$shop->logo);
                              $logstyle="";
                            }else{
                              $logo=asset('front/assets/imgs/default.png');
                              $logstyle="width:100%;height:250px";
                            }

                            ?>
                            <a href="{{ route('shop.visit', $shop->slug) }}" class="overflow-hidden size-64px rounded-content" style="border: 1px solid #e5e5e5;
                                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                <img class="lazyload h-64px  mx-auto"
                                    
                                    src="{{  $logo }}"
                                    >
                            </a>
                            <div class="ml-3">
                                <!-- Shop Name & Verification Status -->
                                <a href="{{ route('shop.visit', $shop->slug) }}"
                                    class="text-dark d-block fs-16 fw-700">
                                    {{ $shop->name }}
                                    @if ($shop->verification_status == 1)
                                        <span class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                                <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                                    <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#3490f3"/>
                                                </g>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                                <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                                    <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="red"/>
                                                </g>
                                            </svg>
                                        </span>
                                    @endif
                                </a>
                                <!-- Ratting -->
                                <div class="rating rating-mr-1 text-dark">
                                    {{ render_newStarRating($shop->rating) }}
                                    <span class="opacity-60 fs-12">({{ $shop->num_of_reviews }}
                                        {{ tran('Reviews') }})</span>
                                </div>
                                <!-- Address -->
                                <div class="location fs-12 opacity-70 text-dark mt-1">{{ $shop->address }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col pl-5 pl-md-0 ml-5 ml-md-0">
                        <div class="d-lg-flex align-items-center justify-content-lg-end">
                            <div class="d-md-flex justify-content-md-end align-items-md-baseline">
                                <!-- Member Since -->
                                <div style="padding:15px;" class="pr-md-3 mt-2 mt-md-0 @if ($shop->facebook || $shop->instagram || $shop->google || $shop->twitter || $shop->youtube) border-lg-right @endif">
                                    <div class="fs-10 fw-400 text-secondary">{{ tran('Member Since') }}</div>
                                    <div class="mt-1 fs-16 fw-700 text-secondary">{{ date('d M Y',strtotime($shop->created_at)) }}</div>
                                </div>
                                
                                <!-- Social Links -->
                                @if ($shop->facebook || $shop->instagram || $shop->google || $shop->twitter || $shop->youtube)
                                    <div style="padding:15px;" class="pl-md-3 pr-lg-3 mt-2 mt-md-0">
                                        <span class="fs-10 fw-400 text-secondary">{{ tran('Social Media') }}</span><br>
                                        <ul class="social-md colored-light list-inline mb-0 mt-1">
                                            @if ($shop->facebook)
                                            <li class="list-inline-item mr-2">
                                                <a href="{{ $shop->facebook }}" 
                                                    target="_blank">
                                                    <i class="fa-brands fa-facebook"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if ($shop->instagram)
                                            <li class="list-inline-item mr-2">
                                                <a href="{{ $shop->instagram }}"
                                                    target="_blank">
                                                    <i class="fa-brands fa-square-instagram"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if ($shop->google)
                                            <li class="list-inline-item mr-2">
                                                <a href="{{ $shop->google }}" 
                                                    target="_blank">
                                                    <i class="fa-brands fa-google"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if ($shop->twitter)
                                            <li class="list-inline-item mr-2">
                                                <a href="{{ $shop->twitter }}" 
                                                    target="_blank">
                                                    <i class="fa-brands fa-twitter"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if ($shop->youtube)
                                            <li class="list-inline-item">
                                                <a href="{{ $shop->youtube }}" 
                                                    target="_blank">
                                                    <i class="fa-brands fa-youtube"></i>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        
    @if (!isset($type))
        @php
            $feature_products = $shop->user->products->where('published', 1)->where('approved', 1)->where('seller_featured', 1);
        @endphp
        @if (count($feature_products) > 0)
            <!-- Featured Products -->
            <section class="mt-3 mb-3" id="section_featured">
                <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-4 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                            <span class="">{{ tran('Featured Products') }}</span>
                        </h3>
                        <!-- Links -->
                        
                    </div>
                    <!-- Products Section -->
                    <div class="px-sm-3">
                            <div class="list-products-5 mt-20">
                            @foreach ($feature_products as $key => $product)
               
                @php
               $discount_precentage=0;
              $total = 0;
              $total += $product->reviews->count();
              if(home_price($product) != home_discounted_price($product)){
              if(discount_in_percentage($product) > 0){
              $discount_precentage=discount_in_percentage($product);
              } 
            }
              @endphp
                        <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$product -> id}}" aria-label="Compare"></a>
                  <a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$product->id}}" data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                    
                  <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$product -> slug)}}"><img src="{{$product->thumbnail_img ? asset('assets/images/products/'.$product->thumbnail_img) :$product -> images[0] -> photo ?? ''}}" alt="Ecom"></a></div>
                    <div class="info-right"><a class="font-xs color-gray-500" href="#">{{$product->brand_id ? $product->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$product -> slug)}}">{{$product->name}} </a>
                      <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                      <div class="mt-20 box-btn-cart"><input type="hidden" value="1" id="quantity{{$product->id}}"><button class="btn btn-cart cart-addition" data-product-url="{{route('site.cart.add','outer')}}"  data-product-id="{{$product->id}}">Add To Cart</button></div>
                      <ul class="list-features">
                      <li><?php echo (strlen($product->description) <  50) ? $product->description : strip_tags(substr($product->description,0,50)).'...' ;?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                        @endforeach
                    </div>
                            
                      
                    </div>
                </div>
            </section>
        @endif
        
        <!-- Banner Slider -->
        @if ($shop->sliders != null)
        <section class="mt-3 mb-3">
            <div class="container">       
                  <section class="section-box d-block">
                  <div class="banner-hero banner-home5 pt-0 pb-0">
                    <div class="box-swiper">
                      <div class="swiper-container swiper-group-1">
                        <div class="swiper-wrapper">
                          <!-- Banner full width 1 -->
                          @foreach (explode(',',$shop->sliders) as $key => $banner)
                          <div class="swiper-slide">
                          
                            <img src="{{asset('assets/images/shops/'.$banner)}}" width="100%">
                          </div>
                          @endforeach
                        </div>
                        <div class="swiper-pagination swiper-pagination-1"></div>
                      </div>
                    </div>
                  </div>
                </section>
                </div>
           </section>
        @endif
        
        <!-- Coupons -->
        @php
            $coupons = \App\Models\Coupon::where('user_id', $shop->user->id)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
        @endphp
        @if (count($coupons)>0)
        <section class="section-box bg-home9" style="padding-top:25px !important;"> 
          <div class="container"> 
          <div class="row"> 
            <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                    {{tran('Coupons')}}
                   </span>
               
                </h3>
            <!-- Links -->
            <div class="d-flex">
                    <a class="text-blue fs-12 fw-700 hov-text-primary" href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'cupons']) }}">{{ tran('View All') }}</a>
                </div>   
                <br><br>
                @foreach ($coupons->take(10) as $key => $coupon)
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
           
            @endforeach
            </div>
        </div>
      </section>
        @endif

        @if ($shop->banner_full_width_1)
        <section class="section-box d-block">
        <div class="banner-hero banner-home5 pt-0 pb-0">
          <div class="box-swiper">
            <div class="swiper-container swiper-group-1">
              <div class="swiper-wrapper">
                <!-- Banner full width 1 -->
                @foreach (explode(',',$shop->banner_full_width_1) as $key => $banner)
                <div class="swiper-slide">
                
                  <img src="{{asset('assets/images/shops/'.$banner)}}" width="100%">
                </div>
                @endforeach
              </div>
              <div class="swiper-pagination swiper-pagination-1"></div>
            </div>
          </div>
        </div>
      </section>
      @endif
      
      <br>
        @if($shop->banners_half_width)
            <!-- Banner half width -->
            <section class="section-box d-block">
        <div class="banner-hero banner-home5 pt-0 pb-0">
          <div class="box-swiper">
            <div class="swiper-container swiper-group-1">
              <div class="swiper-wrapper">
                <!-- Banner full width 1 -->
                @foreach (explode(',',$shop->banners_half_width) as $key => $banner)
                <div class="swiper-slide">
                
                  <img src="{{asset('assets/images/shops/'.$banner)}}" width="100%">
                </div>
                @endforeach
              </div>
              <div class="swiper-pagination swiper-pagination-1"></div>
            </div>
          </div>
        </div>
      </section>
        @endif

    @endif

    <section class="mb-3 mt-3" id="section_types">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-4 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                        @if (!isset($type))
                            {{ tran('New Arrival Products')}}
                        @elseif ($type == 'top-selling')
                            {{ tran('Top Selling')}}
                        @elseif ($type == 'cupons')
                            {{ tran('All Cupons')}}
                        @endif
                    </span>
                </h3>
                @if (!isset($type))
                    <!-- Links -->
                    
                @endif
            </div>
            
            @php
                if (!isset($type)){
                    $products = \App\Models\Product::where('user_id', $shop->user->id)->where('published', 1)->where('approved', 1)->orderBy('created_at', 'desc')->limit(15)->get();
                }
                elseif ($type == 'top-selling'){
                    $products = \App\Models\Product::where('user_id', $shop->user->id)->where('published', 1)->where('approved', 1)->where('num_of_sale', '>',0)->orderBy('num_of_sale', 'desc')->paginate(24);
                }
                elseif ($type == 'cupons'){
                    $coupons = \App\Models\Coupon::where('user_id', $shop->user->id)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(24);
                }
            @endphp

            @if (!isset($type))
                <!-- New Arrival Products Section -->
                <div class="px-sm-3 pb-3">
                <div class="list-products-5 mt-20">
                @foreach ($products as $key => $product)
                @php
               $discount_precentage=0;
              $total = 0;
              $total += $product->reviews->count();
              if(home_price($product) != home_discounted_price($product)){
              if(discount_in_percentage($product) > 0){
              $discount_precentage=discount_in_percentage($product);
              } 
            }
              @endphp
                        <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$product -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$product->id}}" data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                    <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$product -> slug)}}"><img src="{{$product->thumbnail_img ? asset('assets/images/products/'.$product->thumbnail_img) :$product -> images[0] -> photo ?? ''}}" alt="Ecom"></a></div>
                    <div class="info-right"><a class="font-xs color-gray-500" href="$">{{$product->brand_id ? $product->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$product -> slug)}}">{{$product->name}} </a>
                      <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                      <div class="mt-20 box-btn-cart"><input type="hidden" value="1" id="quantity{{$product->id}}"><button class="btn btn-cart cart-addition" data-product-url="{{route('site.cart.add','outer')}}"  data-product-id="{{$product->id}}">Add To Cart</button></div>
                      <ul class="list-features">
                      <li><?php echo (strlen($product->description) <  50) ? $product->description : strip_tags(substr($product->description,0,50)).'...' ;?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                        @endforeach
                    </div>
                </div>

                @if ($shop->banner_full_width_2)
                    <!-- Banner full width 2 -->
                    <section class="section-box d-block">
                <div class="banner-hero banner-home5 pt-0 pb-0">
                <div class="box-swiper">
                    <div class="swiper-container swiper-group-1">
                    <div class="swiper-wrapper">
                        <!-- Banner full width 1 -->
                        @foreach (explode(',',$shop->banner_full_width_2) as $key => $banner)
                        <div class="swiper-slide">
                        
                        <img src="{{asset('assets/images/shops/'.$banner)}}" width="100%">
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination swiper-pagination-1"></div>
                    </div>
                </div>
                </div>
            </section>
              @endif
                

                  <!-- All Coupons Section -->
                @elseif ($type == 'cupons')
                @if($coupons->count() > 0)
          <section class="section-box bg-home9" style="padding-top:25px !important;"> 
          <div class="container"> 
          <div class="row"> 
                @foreach ($coupons as $key => $coupon)
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
                <span class="fs-12 pb-lg-3 d-block m-auto ">
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
           
            @endforeach
            </div>
        </div>
      </section>
      @endif

            <div class="pagination">
                    {{ $coupons->links() }}
                </div>


                @elseif ($type == 'all-products')
                <!-- All Products Section -->
                <div class="section-box shop-template mt-30">
                <form class="" id="search-form" action="" method="GET">
                <div class="container">
          <div class="row">
            <div class="col-lg-9 order-first order-lg-last">
           
              <div class="box-filters mt-0 pb-5 border-bottom">
                <div class="row"><?php if(!isset($limit)){$current_showing=($products->currentPage() - 1)*$products->perPage()+1; $to_showing=($products->currentpage()-1) * $products->perpage() + $products->count();}?> 
                  <div class="col-xl-10 col-lg-9 mb-10 text-lg-end text-center">@if(!isset($limit))<span class="font-sm color-gray-900 font-medium border-1-right span">Showing {{($products->currentPage() - 1)*$products->perPage()+1}}&ndash;{{$to_showing}} of {{$products->total()}} results</span>@endif
                    <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">{{tran('Sort by')}} :</span>
                      <div class="dropdown dropdown-sort">
                        <select class="form-control" name="sort_by" style="margin: 0px;" id="sort" onchange="filter()">
                        <option value="">{{ tran('Sort by')}}</option>
                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ tran('Newest')}}</option>
                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ tran('Oldest')}}</option>
                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ tran('Price low to high')}}</option>
                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ tran('Price high to low')}}</option>
                        </select>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
           
              <div class="row mt-20">
              @isset($products)
              @foreach($products as $product)  
              <?php
                if ($product != null && $product->published != 0){?>

              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card-grid-style-3">
                    <div class="card-grid-inner">
                      <?php
                     
                        $discount_precentage=0;
                        $total = 0;
                        $total += $product->reviews->count();
                        if(home_price($product) != home_discounted_price($product)){
                        if(discount_in_percentage($product) > 0){
                        $discount_precentage=discount_in_percentage($product);
                        }
                        }
                        $img="";
                        if($product->thumbnail_img !=""){
                          $img=asset('assets/images/products/'.$product->thumbnail_img);
                          
                        }else{
                          if($product->images()->count() > 0){
                          $img=$product-> images[0] -> photo ;
                          }
                        }
                     
                        ?>
                      <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 addToWishlist wishlist-addition" href="#" data-product-url="{{route('wishlist.store')}}"   data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}"  data-product-id="{{$product -> id}}"  aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow"  aria-label="Quick view" data-product-id="{{$product->id}}"  data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                      <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$product->slug)}}"><img src="{{$img}}" alt="Ecom"></a></div>
                      <div class="info-right"><a class="font-xs color-gray-500">{{$product->brand_id ? $product->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$product -> slug)}}">{{$product -> name}}</a>
                        <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                        <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                        <div class="mt-20 box-btn-cart">
                    
                        <input type="hidden" value="1" id="quantity{{$product->id}}"> <a class="btn btn-cart cart-addition" data-product-id="{{$product -> id}}"  data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$product -> slug}}" href="#" data-button-action="add-to-cart">Add To Cart</a>
                           
                        </div>
                       <hr>
                        <!-- <ul class="list-features">
                          <li>27-inch (diagonal) Retina 5K display ttttttttttt</li>
                          <li>3.1GHz 6-core 10th-generation Intel Core i5</li>
                          <li>AMD Radeon Pro 5300 graphics</li>
                        </ul> -->
                        <?php echo (strlen($product->description) <  30) ? $product->description : strip_tags(substr($product->description,0,30)).'...' ;?>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <?php
                       }
                      ?>
              
                @endforeach
                @endisset
              
              </div>
              </form>
              <nav>
                <ul class="pagination">
               @if(!isset($limit))
                {!! $products->links() !!}
                @endif
                </ul>
              </nav>
            </div>
           
            @include('front.includes.shop_filter_side',$shop)
          </div>
        </div>
      </div>





            @else
                <!-- Top Selling Products Section -->
                <div class="px-3">
                <div class="list-products-5 mt-20">
                        @foreach ($products as $key => $product)
                        @php
               $discount_precentage=0;
              $total = 0;
              $total += $product->reviews->count();
              if(home_price($product) != home_discounted_price($product)){
              if(discount_in_percentage($product) > 0){
              $discount_precentage=discount_in_percentage($product);
              } 
            }
              @endphp
              <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$product -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$product->id}}" data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                    <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$product -> slug)}}"><img src="{{$product->thumbnail_img ? asset('assets/images/products/'.$product->thumbnail_img) :$product -> images[0] -> photo ?? ''}}" alt="Ecom"></a></div>
                    <div class="info-right"><a class="font-xs color-gray-500" href="#">{{$product->brand_id ? $product->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$product -> slug)}}">{{$product->name}} </a>
                      <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                      <div class="mt-20 box-btn-cart"><input type="hidden" value="1" id="quantity{{$product->id}}"><button class="btn btn-cart cart-addition" data-product-url="{{route('site.cart.add','outer')}}"  data-product-id="{{$product->id}}">Add To Cart</button></div>
                      <ul class="list-features">
                      <li><?php echo (strlen($product->description) < 50  ) ? $product->description : strip_tags(substr($product->description,0,50)).'...' ;?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                        @endforeach
                    </div>
                </div>
                <ul class="pagination">
                {!! $products->links() !!}
                </div>
            @endif
        </div>
    </section>
              </div>
@endsection

@push('js')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>

@endpush
