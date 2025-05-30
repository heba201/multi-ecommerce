@extends('layouts.site')

@section('content')
     <div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="shop-grid.html">{{tran('Shop')}}</a></li>
              <li><a class="font-xs color-gray-500" href="shop-wishlist.html">{{tran('Wishlist')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
      <section class="section-box shop-template">
        <div class="container">
          @include('front.includes.wishlist_box')
       
          @if(isset($newarriavel_products))
          <h4 class="color-brand-3">You may also like</h4>
        
      
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
      
    
      
      <div class="modal fade" id="ModalQuickview" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
          <div class="modal-content apply-job-form">
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-30">
              <div class="row">
                <div class="col-lg-6">
                  <div class="gallery-image">
                    <div class="galleries-2">
                      <div class="detail-gallery">
                        <div class="product-image-slider-2">
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-1.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-2.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-3.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-4.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-5.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-6.jpg" alt="product image"></figure>
                          <figure class="border-radius-10"><img src="assets/imgs/page/product/img-gallery-7.jpg" alt="product image"></figure>
                        </div>
                      </div>
                      <div class="slider-nav-thumbnails-2">
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-1.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-2.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-3.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-4.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-5.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-6.jpg" alt="product image"></div>
                        </div>
                        <div>
                          <div class="item-thumb"><img src="assets/imgs/page/product/img-gallery-7.jpg" alt="product image"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="box-tags">
                    <div class="d-inline-block mr-25"><span class="font-sm font-medium color-gray-900">Category:</span><a class="link" href="#">Smartphones</a></div>
                    <div class="d-inline-block"><span class="font-sm font-medium color-gray-900">Tags:</span><a class="link" href="#">Blue</a>,<a class="link" href="#">Smartphone</a></div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="product-info">
                    <h5 class="mb-15">SAMSUNG Galaxy S22 Ultra, 8K Camera & Video, Brightest Display Screen, S Pen Pro</h5>
                    <div class="info-by"><span class="bytext color-gray-500 font-xs font-medium">by</span><a class="byAUthor color-gray-900 font-xs font-medium" href="shop-vendor-list.html"> Ecom Tech</a>
                      <div class="rating d-inline-block"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500 font-medium"> (65 reviews)</span></div>
                    </div>
                    <div class="border-bottom pt-10 mb-20"></div>
                    <div class="box-product-price">
                      <h3 class="color-brand-3 price-main d-inline-block mr-10">$2856.3</h3><span class="color-gray-500 price-line font-xl line-througt">$3225.6</span>
                    </div>
                    <div class="product-description mt-10 color-gray-900">
                      <ul class="list-dot">
                        <li>8k super steady video</li>
                        <li>Nightography plus portait mode</li>
                        <li>50mp photo resolution plus bright display</li>
                        <li>Adaptive color contrast</li>
                        <li>premium design & craftmanship</li>
                        <li>Long lasting battery plus fast charging</li>
                      </ul>
                    </div>
                    <div class="box-product-color mt-10">
                      <p class="font-sm color-gray-900">Color:<span class="color-brand-2 nameColor">Pink Gold</span></p>
                      <ul class="list-colors">
                        <li class="disabled"><img src="assets/imgs/page/product/img-gallery-1.jpg" alt="Ecom" title="Pink"></li>
                        <li><img src="assets/imgs/page/product/img-gallery-2.jpg" alt="Ecom" title="Gold"></li>
                        <li><img src="assets/imgs/page/product/img-gallery-3.jpg" alt="Ecom" title="Pink Gold"></li>
                        <li><img src="assets/imgs/page/product/img-gallery-4.jpg" alt="Ecom" title="Silver"></li>
                        <li class="active"><img src="assets/imgs/page/product/img-gallery-5.jpg" alt="Ecom" title="Pink Gold"></li>
                        <li class="disabled"><img src="assets/imgs/page/product/img-gallery-6.jpg" alt="Ecom" title="Black"></li>
                        <li class="disabled"><img src="assets/imgs/page/product/img-gallery-7.jpg" alt="Ecom" title="Red"></li>
                      </ul>
                    </div>
                    <div class="box-product-style-size mt-10">
                      <div class="row">
                        <div class="col-lg-12 mb-10">
                          <p class="font-sm color-gray-900">Style:<span class="color-brand-2 nameStyle">S22</span></p>
                          <ul class="list-styles">
                            <li class="disabled" title="S22 Ultra">S22 Ultra</li>
                            <li class="active" title="S22">S22</li>
                            <li title="S22 + Standing Cover">S22 + Standing Cover</li>
                          </ul>
                        </div>
                        <div class="col-lg-12 mb-10">
                          <p class="font-sm color-gray-900">Size:<span class="color-brand-2 nameSize">512GB</span></p>
                          <ul class="list-sizes">
                            <li class="disabled" title="1GB">1GB</li>
                            <li class="active" title="512 GB">512 GB</li>
                            <li title="256 GB">256 GB</li>
                            <li title="128 GB">128 GB</li>
                            <li class="disabled" title="64GB">64GB</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="buy-product mt-5">
                      <p class="font-sm mb-10">Quantity</p>
                      <div class="box-quantity">
                        <div class="input-quantity">
                          <input class="font-xl color-brand-3" type="text" value="1"><span class="minus-cart"></span><span class="plus-cart"></span>
                        </div>
                        <div class="button-buy"><a class="btn btn-cart" href="shop-cart.html">Add to cart</a><a class="btn btn-buy" href="shop-checkout.html">Buy now</a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
   
    @push('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.removeFromWishlistd', function (e) {
            e.preventDefault();
            @guest()
                $('.not-loggedin-modal').css('display','block');
            @endguest
            $.ajax({
                type: 'delete',
                url: "",
                data: {
                    'productId': $(this).attr('data-product-id'),
                },
                success: function (data) {
                    location.reload();
                },
                error: function (error) {
                  console.log(error);
                }
            });
        });
    </script>
    @endpush
    @endsection