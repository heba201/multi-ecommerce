@extends('layouts.site')
@section('content')

      <div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="#">{{tran('Shop')}}</a></li>
              <li><a class="font-xs color-gray-500" href="#">{{tran('Cart')}}</a></li>
            </ul>
          </div>
        </div>
      </div>


      <section class="section-box shop-template">
        <div class="container">
       @include('front.cart.cart_table')
          @if(isset($newarriavel_products) && count($newarriavel_products) > 0)
          <h4 class="color-brand-3">{{tran('You may also like')}}</h4>
        
      
          <div class="list-products-5 mt-20 mb-40">
            
       
                        @foreach($newarriavel_products as $pro)

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
          <div class="card-grid-style-3">
              <div class="card-grid-inner">
              <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$pro -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$pro -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$pro->id}}" data-product-url="{{route('product.quickview',$pro->id)}}"></a></div>
                <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro->slug)}}"><img src="{{$img}}" alt="Ecom"></a></div>
                <div class="info-right"><a class="font-xs color-gray-500" href="{{route('product.details',$pro->slug)}}">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro->slug)}}">{{$pro->name}}</a>
                  <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                  <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                  <div class="mt-20 box-btn-cart"><a class="btn btn-cart cart-addition" href="#" data-product-slug="{{$pro -> slug}}" data-product-id="{{$pro -> id}}" data-product-url="{{route('site.cart.add','outer')}}">Add To Cart</a></div>
                  <input type="hidden" value="1" id="quantity{{$pro->id}}"> 
                  <ul class="list-features">
                    <li><?php echo (strlen($pro->description) <  50) ? $pro->description : strip_tags(substr($pro->description,0,50)).'...' ;?></li>
           
                  </ul>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          
          @endif
          
          @if($most_viewedproducts ->count() > 0)
          @isset($most_viewedproducts)
          <h4 class="color-brand-3">{{tran('Recently viewed items')}}</h4>
          <div class="row mt-40">
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

            <div class="col-lg-3 col-md-6 col-sm-12">
              <div class="card-grid-style-2 card-grid-none-border hover-up">
                <div class="image-box"><a href="{{route('product.details',$pro -> slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                </div>
                <div class="info-right"><span class="font-xs color-gray-500">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</span><br><a class="color-brand-3 font-xs-bold" href="{{route('product.details',$pro -> slug)}}">{{$pro->name}}</a>
                <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                  <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                </div>
              </div>
            </div>
            @endif
            @endforeach
            @endisset
            @endif
          </div>
        </div>
      </section> 
      @push('js')
      <script>
        function delete_item(idstr){
        
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
                type: 'post',
                url: "{{route('site.cart.removeFromCart')}}",
                data: {
                    'id':idstr,
                    
                },
                success: function (data) {
                 console.log(data);
                 swal({
            text: data.msg,
            icon: data.icon,
          }).
          then((result) => {
            window.location.replace(data.url);
    });
         
                },
                error:function (error) {
                  console.log(error);
                }
            });
          
        }


        $(document).on("click", "#coupon-apply", function() {
            var data = new FormData($('#apply-coupon-form')[0]);
           
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('payment.applyCoupon') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
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
      </script>
       @endpush
@endsection