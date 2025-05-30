@extends('layouts.site')

@section('content')
<style>
  input[type="radio"]:checked{
  background-image: url({{asset('assets/imgs/page/shop/check.svg')}});
    background-repeat: no-repeat;
    background-position: center;
    transition: 0.5s;
}
.checkedcls {
    /* background-color: #253849; */
    background-image: url({{asset('assets/imgs/page/shop/check.svg')}});
    background-repeat: no-repeat;
    background-position: center;
    transition: 0.5s;
}

.input-checkbox{
    position: relative;
    margin-left: 4px;
    width: calc(3em - 4px);
    height: calc(3em - 4px);
    float: left;
    margin: 4px 10px 2px 1px;
    border-radius: 50%;
    vertical-align: middle;
    /* border: 5px solid #295282; */
    -webkit-appearance: none;
    outline: none;
    cursor: pointer;
    transition: 0.5s;
}



.radio-toolbar {
  margin: 10px;
}

.radio-toolbar input[type="radio"] {
  opacity: 0;
  position: fixed;
  width: 0;
}

.radio-toolbar label {
    display: inline-block;
    background-color: #ffffff;
    padding: 10px 20px;
    font-family: sans-serif, Arial;
    font-size: 16px;
    border: 2px solid #425a8b;
    border-radius: 4px;
}

.radio-toolbar label:hover {
  background-color: #dfd;
}

.radio-toolbar input[type="radio"]:focus + label {
    border: 2px dashed #444;
}

.radio-toolbar input[type="radio"]:checked + label {
    background-color: #ffffff;
}
.bg-17{
  background-color: #f0f0f0 !important;
}
/*  */
.banner-ads{
  height: 310px !important;
  background: linear-gradient(135deg, rgb(66 90 139) 0%, rgb(91 109 145) 51%, #b2c2e1 51%, #b2c2e1 100%) !important;
}
.banner-ads::after {
  background:none !important;
 
}
@media only screen and (min-width : 605px) {
.banner-ads::before {
  
  
  content: "";
    position: absolute;
    bottom: 0px;
    left: 0px;
    height: 100%;
    width: 532px;
    background: url({{asset('front/assets/imgs/coupon.png')}})  no-repeat bottom right !important;
    background-size: contain;
}
}

@media screen and (max-width: 600px) {

    .box-slide-bg-1 {
      background-size:35% 50% !important;
    }
}

@media only screen and (min-width : 605px) {
  .box-slide-bg-1 {
      background-size:35% 100% !important;
    }
}
</style>

<?php
  if($slider_products->count() > 0){?>
<section class="section-box"  dir="ltr">
        <div class="banner-hero banner-homepage3" dir="ltr">
          <div class="container-banner" dir="ltr">
            <div class="box-swiper" style="height:450px" dir="ltr">
              <div class="swiper-container swiper-group-1 swiper-home-3" dir="ltr">
                <div class="swiper-wrapper pt-5" dir="ltr">
                <?php
                   
                      foreach($slider_products as $product){
                        $discount_precentage=0;
                        $total = 0;
                        $total += $product->reviews->count();
                       
                        if(home_price($product) != home_discounted_price($product)){
                        if(discount_in_percentage($product) > 0){
                        $discount_precentage=discount_in_percentage($product);
                        
                        }
                      }
                      ?> 
                <div class="swiper-slide">
                    <div class="box-slide-bg-1" style="background: url({{$product->thumbnail_img ? asset('assets/images/products/'.$product->thumbnail_img) :$product -> images[0] -> photo ?? ''}}) no-repeat  right;"><span class="label-green text-uppercase">{{tran('new arrival')}}</span>
                  
                    <h2 class="font-68 mt-20">{{  (strlen($product ->name) < 15) ? $product ->name:substr($product ->name,0,15).'...'}}</h2>
                      <div class="mt-10">
                       
                      </div>
                      <div class="mt-30 mb-120"><a class="btn btn-brand-2 btn-gray-1000" href="{{route('category',$product -> category ->slug)}}">{{tran('Shop now')}}</a><a class="btn btn-link text-underline" href="{{route('product.details',$product -> slug)}}">{{tran('Learn more')}}</a></div>
                    </div>
                  </div>
                 
                  <?php
                      }
                  ?>
                
                </div>
                <div class="swiper-pagination swiper-pagination-1"></div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php
             }
      ?>

      <!------------ slider for brands---------------------->
      
      <div class="section-box" style="padding-top:10px">
        <div class="container">
          <div class="list-brands list-none-border">
            <div class="box-swiper">
              <div class="swiper-container swiper-group-10">
                <div class="swiper-wrapper">
                @isset($brands)
                @foreach($brands as $brand) 
                @php
                $bimg=asset('assets/images/default.png');
                
                if(count(explode("brands/",$brand->logo)) > 1){
                $bimg=$brand->logo;
             
                }
                @endphp
                  <div class="swiper-slide"><a href="{{route('brand',$brand->slug)}}"><img src="{{$bimg}}" alt="Ecom" ></a></div>
                    @endforeach
                      @endisset
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     <!------------ slider for brands---------------------->
     @if($featured_categories->count() > 0)
      <section class="section-box mt-30">
        <div class="container">
          <div class="border-bottom pb-25 head-main">
            <h3>{{tran('Featured Categories')}}</h3>
            <p class="font-base">{{tran('Choose your necessary products from this feature categories.')}}</p>
            <!-- Button slider-->
            <div class="box-button-slider">
              <div class="swiper-button-next swiper-button-next-group-2"></div>
              <div class="swiper-button-prev swiper-button-prev-group-2"></div>
            </div>
            <!-- End Button slider-->
          </div>
          <div class="mt-10">
            <div class="box-swiper">
              <div class="swiper-container swiper-group-style-2">
                <div class="swiper-wrapper pt-5">
                  <div class="swiper-slide">
                    <ul class="list-9-col">
                      @isset($featured_categories)
                 @foreach($featured_categories as $category)      
                    <li>
                        <div class="box-category hover-up">
                          <div class="image"><a href="{{route('category',$category -> slug )}}"><img src="{{$category->banner}}" alt="Ecom"></a></div>
                          <div class="text-info"><a class="font-sm color-gray-900 font-bold" href="{{route('category',$category -> slug )}}">{{$category->name}}</a>
                            <p class="font-xs color-gray-500">{{$category->products()->count()}} {{tran('items')}}</p>
                          </div>
                        </div>
                      </li>
                      @endforeach
                      @endisset
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
     @endif
      <?php
              $flash_deals=[];
              $atetime_arr=[];
              $flash_id=[];
        $flash_deal_blocks = \App\Models\FlashDeal::where('status', 1)->where('featured', 1)->get();
        if($flash_deal_blocks->count() > 0){
         foreach( $flash_deal_blocks  as  $flash_deal_block){       
         if($flash_deal_block->id !="" && strtotime(date('Y-m-d H:i:s')) >= $flash_deal_block->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal_block->end_date){
          foreach($flash_deal_block->flash_deal_products as  $key=>$value){
            if(!in_array($value->product_id,$flash_deals)){
              if(count($flash_deals) < 5){
                $flash_deals[] = $value->product_id;
              }
            }    
          }
         }
         }
         if(count($flash_deals) > 0){
        ?>
      <section class="section-box pt-50">
        <div class="container">
          <div class="bg-9 box-bdrd-4 pt-35 pb-35 pl-25 pr-25">
            <div class="head-main">
              <div class="row">
                <div class="col-lg-6">
                  <div class="box-icon-flash">
                    <h3 class="mb-5">{{tran('FLASH DEALS')}}</h3>
                    <p class="font-16 font-bold color-gray-900">{{tran('The opportunity will quickly pass. Take it!')}}</p>
                    <p class="font-16 font-bold color-gray-900"><a style="color:#FD9636 !important;padding:10px;" href="{{route('flash_deals.all')}}">{{tran('View All')}}</a></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-0">
              <div class="list-products-5" >
             <?php
       if(count($flash_deals) > 0){
        for($i=0;$i<count($flash_deals);$i++){
          $product = \App\Models\Product::find($flash_deals[$i]);
          $discount_precentage=0;
                        $total = 0;
                        $total += $product->reviews->count();
                        if(home_price($product) != home_discounted_price($product)){
                        if(discount_in_percentage($product) > 0){
                        $discount_precentage=discount_in_percentage($product);
                        }
                        }
          $imgf="";
          if($product->thumbnail_img !=""){
            $imgf=asset('assets/images/products/'.$product->thumbnail_img);
          }else{
           if($product -> images()->count() > 0){
            $imgf= $product -> images[0] -> photo ;
           }
          }
          
          $qty = 0;
          foreach ($product->stocks as $key => $stock) {
              $qty += $stock->qty;
          }
          $av=0;
          $sold=0;
          foreach ($product->orderDetails as $key => $detail) {
          $sold+=$detail->quantity;
          }
          $total_quan=$qty+$sold;
          if($qty > 0){
            $av= (($qty/$total_quan))*100;   
          }
               
         ?>
       
              <div class="card-grid-style-3 hover-show hover-hide-show-cart">
                  <div class="card-grid-inner" >
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$product -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$product->id}}" data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                  <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{$discount_precentage}}%</span>@endif<a href="{{route('product.details',$product -> slug)}}"><img src="{{$imgf}}" alt="Ecom"></a>
        </div>
                  <div class="box-count">
                   <?php
                   $dateTime=date('Y/m/d H:i:s', $product->discount_end_date);
                   ?>
                      <div class="deals-countdown" data-countdown="{{$dateTime}}"></div>
                    </div>
                    <div class="info-right"><span class="font-xs color-gray-500">{{$product->name}}</span><br><a class="color-brand-3 font-sm-bold" href="#"></a>
                    <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info" style="<?php echo App::getLocale()=="ar" ?'margin-right:-12px':''?>"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                      <div class="box-progress box-progress-small">
                        <div class="progress-bar">
                          <div class="progress-bar-inner" style="width: {{$av}}% !important;"></div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6"><span class="font-xs color-gray-500">{{tran('Available')}}:</span><span class="font-xs-bold color-gray-900">{{$qty}}</span></div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-end"><span class="font-xs color-gray-500">{{tran('Sold')}}:</span><span class="font-xs-bold color-gray-900">{{$sold}}</span></div>
                        </div>
                      </div>
                      <div class="mt-20 box-add-cart"><a class="btn btn-cart cart-addition"   data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$product -> slug}}" data-product-id="{{$product -> id}}">Add To Cart</a></div>
                      <input type="hidden" value="1" id="quantity{{$product->id}}">

                      <ul class="list-features">
                        <li> {{$product->est_shipping_days}} - day Delivery. </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <?php
       }
      }
                ?>

              </div>
            </div>
          </div>
        </div>
      </section>
<?php
         }
        }
?>



          @if(get_setting('coupon_system') == 1)
                @php
                $coupons = App\Models\Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
                @endphp
                @if($coupons->count() > 0)
                <section class="section-box mt-30">
                  <div class="container">
                    <div class="banner-ads text-center">
                      <h2 class="color-brand-2 font-46 mb-5">{{ tran('Coupons') }}</h2>
                      <div class="mt-20"><a class="btn btn-brand-2 btn-arrow-right" href="{{ route('coupons.all') }}">{{tran('View All Coupons')}}</a></div>
                    </div>
                  </div>
                </section>
                  @endif
                  @endif


@if(isset($newarrivals_section) && $newarrivals_section->count() > 0)
<section class="mb-3 mt-3" id="section_types">
        <div class="container">
    <!-- Top Section -->
    <div class="d-flex mb-4 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                            {{ tran('New Arrival Products')}}  <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','new_arrivals_products')}}">{{tran('View All')}}</a>
                    </span>
                </h3>
            </div>

     <!-- New Arrival Products Section -->
     <div class="px-sm-3 pb-3">
                <div class="list-products-5 mt-20">
                @foreach ($newarrivals_section as $key => $product)
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

                </div>
              </section>
                  @endif



@if(isset($todays_deal_products) && $todays_deal_products->count() > 0)
<section class="mb-3 mt-3" id="section_types">
        <div class="container">
    <!-- Top Section -->
    <div class="d-flex mb-4 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                            {{ tran('Todays Deal Products')}}   <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','todays_deal_products')}}">{{tran('View All')}}</a>
 
                    </span>
                </h3>
            </div>

     <!-- New Arrival Products Section -->
     <div class="px-sm-3 pb-3">
                <div class="list-products-5 mt-20">
                @foreach ($todays_deal_products as $key => $product)
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

                </div>
              </section>
                  @endif


<?php
if ((isset($trending_products) && $trending_products ->count() > 0) || (isset($trending_best_sellings) && $trending_best_sellings->count() > 0)  || (isset($most_viewedproducts) &&  $most_viewedproducts ->count() > 0 ) || (isset($topbrand_products) && $topbrand_products->count() > 0)){
?>

      <section class="section-box pt-70">
        <div class="container">
          <div class="head-main">
            <div class="row">
              <div class="col-xl-5 col-lg-5">
                <h3 class="mb-5">{{tran('Trending Products')}}</h3>
                <p class="font-base color-gray-500">{{tran('Special products in this month.')}}</p>
              </div>
              <div class="col-xl-7 col-lg-7">
                <ul class="nav nav-tabs nav-tabs-underline text-uppercase" role="tablist">
                @if((isset($trending_products) && $trending_products ->count() > 0) || (isset($best_sellings) && $best_sellings->count() > 0)  || (isset($most_viewedproducts) &&  $most_viewedproducts ->count() > 0 ) || (isset($topbrand_products) && $topbrand_products->count() > 0))
                  <li><a class="active" href="#tab-all" data-bs-toggle="tab" role="tab" aria-controls="tab-all" aria-selected="true">{{tran('All')}}</a></li>
                 @endif
                  @if (isset($trending_best_sellings) && $trending_best_sellings->count() > 0)
                  <li><a href="#tab-bestseller" data-bs-toggle="tab" role="tab" aria-controls="tab-bestseller" aria-selected="true">{{tran('Best selling')}}</a></li>
                 @endif
                 @if((isset($most_viewedproducts) &&  $most_viewedproducts ->count() > 0 ) )
                  <li><a href="#tab-mostviewed" data-bs-toggle="tab" role="tab" aria-controls="tab-mostviewed" aria-selected="true">{{tran('Most viewed')}}</a></li>
                  @endif
                  @if(isset($topbrand_products) && $topbrand_products->count() > 0)
                  <li><a href="#tab-topbrands" data-bs-toggle="tab" role="tab" aria-controls="tab-topbrands" aria-selected="true">{{tran('Top Brands')}}</a></li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
          <div class="tab-content">
          @if((isset($trending_products) && $trending_products ->count() > 0) || (isset($trending_best_sellings) && $trending_best_sellings->count() > 0)  || (isset($most_viewedproducts) &&  $most_viewedproducts ->count() > 0 ) || (isset($topbrand_products) && $topbrand_products->count() > 0))

            <div class="tab-pane fade active show" id="tab-all" role="tabpanel" aria-labelledby="tab-all">
             
            <div class="list-products-5">
              <?php
            if(isset($trending_products)){
             foreach($trending_products as $pro){
             if($pro->trending ==1){
              $discount_precentage=0;
              $total = 0;
              $total += $pro->reviews->count();
              if(home_price($pro) != home_discounted_price($pro)){
              if(discount_in_percentage($pro) > 0){
              $discount_precentage=discount_in_percentage($pro);
              }
              }
              ?>
                <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                    <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$pro -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$pro -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$pro->id}}" data-product-url="{{route('product.quickview',$pro->id)}}"></a></div>
                    <?php
                   $trendimg="";
                    if($pro->thumbnail_img !=""){
                      $trendimg=asset('assets/images/products/'.$pro->thumbnail_img);
                    }else{
                     if($pro -> images()->count() > 0){
                      $trendimg= $pro -> images[0] -> photo ;
                     }
                    }
                    
                    ?>
                    <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro->slug)}}"><img src="{{$trendimg}}" alt="Ecom"></a>
                    </div>
                    <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro->slug)}}">{{$pro->name}}</a>
                    <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>                      
                    <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                      <ul class="list-features">
                        <li><?php echo (strlen($pro->description) <  50) ? $pro->description : strip_tags(substr($pro->description,0,50)).'...' ;?></li>
                      </ul>
                      <div class="mt-20 box-add-cart"><a class="btn btn-cart cart-addition"   data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$pro -> slug}}" data-product-id="{{$pro -> id}}">{{tran('Add To Cart')}}</a></div>
                   <input type="hidden" value="1" id="quantity{{$pro->id}}">
                    </div>
                  </div>
                </div>
                
                <?php
                $product=$pro;
                }
                ?>
            
              
                <?php
                }
              }
                ?>
             

              </div>
              <div class="nav-center">
              <h5>
              <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','trending_products')}}">{{tran('View All')}}</a>
              </h5>
              
              </div>
            </div>
             @endif

            <span id="prospan">  </span>
            @if (isset($trending_best_sellings) && $trending_best_sellings->count() > 0)
            <div class="tab-pane fade" id="tab-bestseller" role="tabpanel" aria-labelledby="tab-bestseller">
              <div class="list-products-5">
              @if(isset($trending_best_sellings))
              @foreach($trending_best_sellings as $best_sell)
              @if($best_sell->trending ==1)
              @php
              $discount_precentage=0;
              $total = 0;
              $total += $best_sell->reviews->count();
              if(home_price($best_sell) != home_discounted_price($best_sell)){
              if(discount_in_percentage($best_sell) > 0){
              $discount_precentage=discount_in_percentage($best_sell);
              }
              }

              $img="";
                    if($best_sell->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$best_sell->thumbnail_img);
                    }else{
                     if($best_sell -> images()->count() > 0){
                      $img= $best_sell -> images[0] -> photo;
                     }
                    }
              @endphp
          

              
              <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$best_sell -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$best_sell -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$best_sell->id}}" data-product-url="{{route('product.quickview',$best_sell->id)}}"></a></div>
                   <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$best_sell -> slug)}}"><img src="{{ $img}}" alt="Ecom"></a>
                    </div>
                    <div class="info-right"><span class="font-xs color-gray-500">{{$best_sell->brand_id ? $best_sell->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$best_sell -> slug)}}">{{$best_sell->name}}</a>
                      <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($best_sell) ?? home_price($best_sell) }}</strong>@if(home_discounted_price($best_sell) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($best_sell)}}</span>@endif</div>
                      <ul class="list-features">
                        <li> 
                        <?php echo (strlen($best_sell->description) <  50) ? $best_sell->description : strip_tags(substr($best_sell->description,0,50)).'...' ;?>
                      </li>
                      </ul>
                      <div class="mt-20 box-add-cart"><a class="btn btn-cart" href="{{route('product.details',$best_sell -> slug)}}">{{tran("Add To Cart")}}</a></div>
                    </div>
                  </div>
                </div>
                @endif
                @endforeach
                @endif
              </div>
              <div class="nav-center">
              <h5>
              <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','trending_best_sellings')}}">{{tran('View All')}}</a>
              </h5>
              
              </div>
            </div>
              @endif 

              @if(isset($most_viewedproducts) && $most_viewedproducts->count() > 0)
            <div class="tab-pane fade" id="tab-mostviewed" role="tabpanel" aria-labelledby="tab-mostviewed">
              <div class="list-products-5">
               
              @isset($most_viewedproducts)
                        @foreach($most_viewedproducts as $pro)
                        @if($pro->trending ==1)
                        @php
                        $discount_precentage=0;
                        $total = 0;
                        $total += $pro->reviews->count();
                        if(home_price($pro) != home_discounted_price($pro)){
                        if(discount_in_percentage($pro) > 0){
                        $discount_precentage=discount_in_percentage($pro);
                        }
                        }

                        $img="";
                    if($pro->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$pro->thumbnail_img);
                    }else{
                     if($pro -> images()->count() > 0){
                      $img= $pro -> images[0] -> photo;
                     }
                    }
                        @endphp
              <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$pro -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$pro -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$pro->id}}" data-product-url="{{route('product.quickview',$pro->id)}}"></a></div>
                    <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{$discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro -> slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                    </div>
                    <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro -> slug)}}">{{$pro->name}}</a>
                    <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                    <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                      <ul class="list-features">
                        <li><?php echo (strlen($pro->description) <  50) ? $pro->description : strip_tags(substr($pro->description,0,50)).'...' ;?></li>
                        
                      </ul>
                      <div class="mt-20 box-add-cart"><a class="btn btn-cart cart-addition"   data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$pro -> slug}}" data-product-id="{{$pro -> id}}">{{tran('Add To Cart')}}</a></div>

                      </div>
                  </div>
                </div>
                @endif
                @endforeach
                @endisset

                
              </div>
              <div class="nav-center">
              <h5>
              <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','trending_most_viewedproducts')}}">{{tran('View All')}}</a>
              </h5>
              
              </div>
            </div>
            @endif
            
            @if(isset($most_viewedproducts) && $most_viewedproducts->count() > 0)
            <div class="tab-pane fade" id="tab-topbrands" role="tabpanel" aria-labelledby="tab-topbrands">
              <div class="list-products-5">
              @isset($topbrand_products)
              @foreach($topbrand_products as $pro)
              @if($pro->trending ==1)
              @php
              $discount_precentage=0;
              $total = 0;
              $total += $pro->reviews->count();
              if(home_price($pro) != home_discounted_price($pro)){
              if(discount_in_percentage($pro) > 0){
              $discount_precentage=discount_in_percentage($pro);
              }
              }

              
              $img="";
                    if($pro->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$pro->thumbnail_img);
                    }else{
                     if($pro -> images()->count() > 0){
                      $img= $pro -> images[0] -> photo;
                     }
                    }
              @endphp
                <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$pro -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$pro -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$pro->id}}" data-product-url="{{route('product.quickview',$pro->id)}}"></a></div>
                   <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro -> slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                    </div>
                    <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro -> slug)}}">{{$pro->name}}</a>
                      <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                      <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                      <ul class="list-features">
                        <li><?php echo (strlen($pro->description) <  50) ? $pro->description : strip_tags(substr($pro->description,0,50)).'...' ;?></li>
                      </ul>
                      <div class="mt-20 box-add-cart"><a class="btn btn-cart cart-addition"   data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$pro -> slug}}" data-product-id="{{$pro -> id}}">{{tran('Add To Cart')}}</a></div>

                      </div>
                  </div>
                </div>
                @endif
                @endforeach
                @endisset
              </div>
              <div class="nav-center">
              <h5>
              <a class="btn btn-view-all font-md-bold" href="{{route('products.view_all','trending_topbrand_products')}}">{{tran('View All')}}</a>
              </h5>
              
              </div>
            </div>
            @endif
          </div>
        </div>
      </section>
      <?php
      }
      ?>


      


      @if($topbrands->isNotEmpty())
      <section class="section-box bg-gray-50 pt-50 pb-50 mt-50"> 
      <div class="container">
          <div class="head-main bd-gray-200">
            <div class="row">
              <div class="col-xl-7 col-lg-6">
                <h3 class="mb-5">{{tran("Top Brands")}}</h3>
                <p class="font-base color-gray-500">{{tran("Special products in this month.")}}</p>
              </div>
              <div class="col-xl-5 col-lg-6">
                <ul class="nav nav-tabs text-uppercase" role="tablist">
                  <li><a class="active" href="#tab-2-all" data-bs-toggle="tab" role="tab" aria-controls="tab-2-all" aria-selected="true">All</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="tab-content">
            <div class="tab-pane fade active show" id="tab-2-all" role="tabpanel" aria-labelledby="tab-2-all">
              <div class="row">
           
              <?php
             
               foreach($topbrands as $topbrand){
                $pro_name_str="";
                $pro_arr=[];
               if($topbrand->products()->count() > 0){
               
               foreach($topbrand->products->take(4) as $key=>$value){
               $pro_arr[]=$value->name;
               }
              }
               if(count($pro_arr) > 0){
               $pro_name_str=implode(",",$pro_arr);
               $pro_name_str= substr($pro_name_str,0,50) .'...';
               }
               $topbimg=asset('assets/images/default.png');
               if(count(explode("brands/",$topbrand->logo)) > 1){
                $topbimg=$topbrand->logo;
               }
               ?>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="card-grid-style-4">
                    <div class="card-grid-inner">
                      <div class="image-box"><a href="{{route('brand',$topbrand->slug)}}"><img src="{{$topbimg}}" alt="Ecom"></a></div>
                      <div class="info-right">
                        <p class="font-xs color-brand-3">{{$pro_name_str}}</p>
                        <div class="divide mb-5"></div>
                        <div class="font-lg-bold color-brand-3"></div>
                        <div class="box-link"><a class="btn btn-link-brand-2 btn-arrow-brand-2 btn-arrow-small text-lowercase pt-0 pb-0" href="{{route('brand',$topbrand->slug)}}">{{tran('Shop Now')}}</a></div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
               }
             ?>
                
              </div>
            </div>
          </div>
        </div>
      </section>
      @endisset

      <?php
      $div=12;
      $count=0;
       if(isset($best_sellings) && $best_sellings ->count() > 0){
        $count++;
       }
       if(isset($featured_products) && $featured_products ->count() > 0){
        $count++;
       }
       if(isset($most_viewedproductstwo) && $most_viewedproductstwo ->count() > 0){
        $count++;
       }

       if(isset($trending_products) && $trending_products ->count() > 0){
        $count++;
       }

       if($count > 0){
        $result=$div/$count;
       }else $result=12;
       $col="col-lg-".$result;
      ?>
      <section class="section-box mt-50">
        <div class="container">
          <div class="row">
          @if (isset($best_sellings) && $best_sellings ->count() > 0)
            <div class="{{$col}} col-md-6 col-sm-12">
              <div class="box-slider-item">
                <div class="head">
                  <h5>{{tran("Best selling")}}<span style="float:right;padding-right:35px;"><a style="color:#fd9636" href="{{route('products.view_all','best_sellings')}}">{{tran('All')}}</a></span></h5>
                  
                </div>
                <div class="content-slider">
                  <div class="box-swiper">
                    <div class="swiper-container swiper-best-seller">
                      <div class="swiper-wrapper pt-5">
                        <div class="swiper-slide">
                          
                        @if(isset($best_sellings))
                        @foreach($best_sellings as $best_sell)
                        @php
                        $discount_precentage=0;
                        $total = 0;
                        $total += $best_sell->reviews->count();
                        if(home_price($best_sell) != home_discounted_price($best_sell)){
                        if(discount_in_percentage($best_sell) > 0){
                        $discount_precentage=discount_in_percentage($best_sell);
                        }
                        }

                        $img="";
                    if($best_sell->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$best_sell->thumbnail_img);
                    }else{
                     if($best_sell -> images()->count() > 0){
                      $img= $best_sell -> images[0] -> photo ;
                     }
                    }
                        @endphp
                        <div class="card-grid-style-2 hover-up">
                        <div class="image-box"><a href="{{route('product.details',$best_sell -> slug)}}"><img src="{{$img}}" alt="Ecom"></a></div>

                        <div class="info-right"><span class="font-xs color-gray-500">{{$best_sell->brand_id ? $best_sell->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$best_sell -> slug)}}">{{$best_sell->name}}</a>
                              <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                              <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($best_sell) ?? home_price($best_sell) }}</strong>@if(home_discounted_price($best_sell) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($best_sell)}}</span>@endif</div>
                            </div>
                          </div>
                          @endforeach
                            @endif
                         
                        </div>
                    
                      </div>
                    </div>
                   <div class="swiper-button-next swiper-button-next-style-2 swiper-button-next-bestseller"></div>
                    <div class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-bestseller"></div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            
            @if(isset($featured_products) && $featured_products ->count() > 0)
            <div class="{{$col}} col-md-6 col-sm-12">
              <div class="box-slider-item">
                <div class="head">
                  <h5>{{tran('Featured products')}}<span style="float:right;padding-right:35px;"><a style="color:#fd9636" href="{{route('products.view_all','featured_products')}}">{{tran('All')}}</a></span></h5>
                </div>
                <div class="content-slider">
                  <div class="box-swiper">
                    <div class="swiper-container swiper-featured">
                      <div class="swiper-wrapper pt-5">
                        <div class="swiper-slide">
                          @if(isset($featured_products))
                          @foreach($featured_products as $featured_product)
                          @php
                          $discount_precentage=0;
                          $total = 0;
                          $total += $featured_product->reviews->count();
                          if(home_price($featured_product) != home_discounted_price($featured_product)){
                          if(discount_in_percentage($featured_product) > 0){
                          $discount_precentage=discount_in_percentage($featured_product);
                          }
                          }

                          $img="";
                    if($featured_product->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$featured_product->thumbnail_img);
                    }else{
                     if($featured_product -> images()->count() > 0){
                      $img= $featured_product -> images[0] -> photo ;
                     }
                    }
                          @endphp
                        <div class="card-grid-style-2  hover-up">
                            <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$featured_product->slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                            </div>
                            <div class="info-right"><span class="font-xs color-gray-500">{{$featured_product->name}}</span><br><a class="color-brand-3 font-xs-bold" href="{{route('product.details',$featured_product->slug)}}"></a>
                            <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                              <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($featured_product) ?? home_price($featured_product) }}</strong>@if(home_discounted_price($featured_product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($featured_product)}}</span>@endif</div>
                            </div>
                          </div>
                          @endforeach
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="swiper-button-next swiper-button-next-style-2 swiper-button-next-featured"></div>
                    <div class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-featured"></div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            
            @if(isset($most_viewedproductstwo) && $most_viewedproductstwo ->count() > 0)
            <div class="{{$col}} col-md-6 col-sm-12">
              <div class="box-slider-item">
                <div class="head">
                  <h5>{{tran("Most viewed")}}<span style="float:right;padding-right:35px;"><a style="color:#fd9636" href="{{route('products.view_all','most_viewedproducts')}}">{{tran('All')}}</a></span></h5>
                </div>
                <div class="content-slider">
                  <div class="box-swiper">
                    <div class="swiper-container swiper-mostviewed">
                      <div class="swiper-wrapper pt-5">
                        <div class="swiper-slide">
                        @isset($most_viewedproductstwo)
                        @foreach($most_viewedproductstwo as $pro)
                        @php
                        $discount_precentage=0;
                        $total = 0;
                        $total += $pro->reviews->count();
                        if(home_price($pro) != home_discounted_price($pro)){
                        if(discount_in_percentage($pro) > 0){
                        $discount_precentage=discount_in_percentage($pro);
                        }
                        }

                        $img="";
                    if($pro->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$pro->thumbnail_img);
                    }else{
                     if($pro -> images()->count() > 0){
                      $img= $pro -> images[0] -> photo ;
                     }
                    }
                        @endphp
                        <div class="card-grid-style-2 hover-up">
                            <div class="image-box"><a href="{{route('product.details',$pro -> slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                            </div>
                            <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro -> slug)}}">{{$pro->name}}</a>
                            <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                            <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                            </div>
                          </div>
                          @endforeach
                          @endisset
                        </div>
                      </div>
                    </div>
                    <div class="swiper-button-next swiper-button-next-style-2 swiper-button-next-mostviewed"></div>
                    <div class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-mostviewed"></div>
                  </div>
                </div>
              </div>
            </div>
            @endif

            @if(isset($trending_products) && $trending_products ->count() > 0)
            <div class="{{$col}} col-md-6 col-sm-12">
              <div class="box-slider-item">
                <div class="head">
                  <h5>{{tran("Trending")}}<span style="float:right;padding-right:35px;"><a style="color:#fd9636" href="{{route('products.view_all','trending_products')}}">{{tran('All')}}</a></span></h5>
                </div>
                <div class="content-slider">
                  <div class="box-swiper">
                    <div class="swiper-container swiper-trending">
                      <div class="swiper-wrapper pt-5">
                        <div class="swiper-slide">
                        @if(isset($trending_products))
                        @foreach($trending_products as $pro)

                        @php
                          $discount_precentage=0;
                          $total = 0;
                          $total += $pro->reviews->count();
                          if(home_price($pro) != home_discounted_price($pro)){
                          if(discount_in_percentage($pro) > 0){
                          $discount_precentage=discount_in_percentage($pro);
                          }
                          }

                          
                        $img="";
                    if($pro->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$pro->thumbnail_img);
                    }else{
                     if($pro -> images()->count() > 0){
                      $img= $pro -> images[0] -> photo ;
                     }
                    }
                          @endphp
                        
                        <div class="card-grid-style-2  hover-up">
                        <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro->slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                         </div>
                            <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-xs-bold" href="{{route('product.details',$pro->slug)}}">{{$pro->name}}</a>
                            <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>                      
                            <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                            </div>
                          </div>
                          @endforeach
                         @endif
                        </div>
                      </div>
                    </div>
                    <div class="swiper-button-next swiper-button-next-style-2 swiper-button-next-trending"></div>
                    <div class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-trending"></div>
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </section>



      <section class="section-box mt-50">
        <div class="container">
      
          <div class="row mt-60">
            
          @isset($categories)
          @foreach($categories as $category)
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
              <div class="card-grid-style-2 card-grid-style-2-small">
               @php
              $catimg=asset('assets/images/default.png');
                
                if(count(explode("categories/",$category->banner)) > 1){
                $catimg=$category->banner;
             
                }
                 @endphp
                <div class="image-box"><a href="{{route('category',$category -> slug )}}"><img src="{{$catimg}}" alt="Ecom"></a>
                  <div class="mt-10 text-center"><a class="btn btn-gray" href="{{route('category',$category -> slug )}}">{{tran('View all')}}</a></div>
                </div>
                <div class="info-right"><a class="color-brand-3 font-sm-bold" href="{{route('category',$category -> slug )}}">
                    <h6>{{$category->name}}</h6></a>
                  <ul class="list-links-disc">
                    @if($category->products()->count() > 0)
                    
                       @foreach($category->products->take(4) as $key=>$value)
                    <li><a class="font-sm" href="{{route('product.details',$value -> slug)}}">{{$value->name}}</a></li>
                    @endforeach
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            @endforeach
            @endisset
          </div>
        </div>
      </section>
       
      
  @push('js')
  
@endpush
@endsection