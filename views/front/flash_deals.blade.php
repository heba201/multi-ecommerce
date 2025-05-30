@extends('layouts.site')

@section('content')

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
                $flash_deals[] = $value->product_id;
            }    
          }
         }
         }
         if(count($flash_deals) > 0){
        ?>
      <section class="section-box pt-50" style="padding-bottom:50px;">
        <div class="container">
          <div class="bg-9 box-bdrd-4 pt-35 pb-35 pl-25 pr-25">
            <div class="head-main">
              <div class="row">
                <div class="col-lg-6">
                  <div class="box-icon-flash">
                    <h3 class="mb-5">{{tran('FLASH DEALS')}}</h3>
                    <p class="font-16 font-bold color-gray-900">{{tran('The opportunity will quickly pass. Take it!')}}</p>
                    <p class="font-16 font-bold color-gray-900"><a>{{tran('View All')}}</a></p>
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

@endsection