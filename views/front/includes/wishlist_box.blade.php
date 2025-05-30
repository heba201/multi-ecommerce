<div class="box-wishlist" id="box-wishlist">
            <div class="head-wishlist">
              <div class="item-wishlist">
                <div class="wishlist-cb">
                 
                </div>
                <div class="wishlist-product"><span class="font-md-bold color-brand-3">Product</span></div>
                <div class="wishlist-price"><span class="font-md-bold color-brand-3">Price</span></div>
                <div class="wishlist-status"><span class="font-md-bold color-brand-3">Stock status</span></div>
                <div class="wishlist-action"><span class="font-md-bold color-brand-3">Action</span></div>
                <div class="wishlist-remove"><span class="font-md-bold color-brand-3">Remove</span></div>
              </div>
            </div>

            <div class="content-wishlist">

            @isset($products)
            @foreach($products as $product)
            @php
            $discount_precentage=0;
              $total = 0;
              if($product->reviews){
                $total += $product->reviews->count();
              }
              
              if(home_price($product) != home_discounted_price($product)){
              if(discount_in_percentage($product) > 0){
              $discount_precentage=discount_in_percentage($product);
              }
              }

              
              $img="";
                    if($product->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$product->thumbnail_img);
                    }else{
                     if($product -> images()->count() > 0){
                      $img= $product -> images[0] -> photo;
                     }
                    }


                    $qty = 0;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                    @endphp

              <div class="item-wishlist">
                
                <div class="wishlist-product">
                  <div class="product-wishlist">
                    <div class="product-image"><a href="{{route('product.details',$product -> slug)}}"><img src="{{$img}}" alt="Ecom"></a></div>
                    <div class="product-info"><a href="{{route('product.details',$product -> slug)}}">
                        <h6 class="color-brand-3">{{$product -> name}}</h6></a>
                        <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                    </div>
                  </div>
                </div>
                <div class="wishlist-price">
                  <h4 class="color-brand-3">{{ home_discounted_price($product) ?? home_price($product) }}</h4>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif
                </div>
                <div class="wishlist-status">
                @if($product->stock_visibility_state == 'quantity')
                <span class="btn btn-gray font-md-bold color-brand-3">({{ $qty .' ' .tran('available') }})</span>
                @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                <span class="btn btn-gray font-md-bold color-brand-3"><i class="fa fa-check-square-o" aria-hidden="true"></i>{{tran('in stock')}}</span>
                @elseif($product->stock_visibility_state == 'text' && $qty < 1)
                <span class="btn btn-gray font-md-bold color-brand-3"><i class="fa fa-check-square-o" aria-hidden="true"></i>{{tran('out of stock') }}</span>
                @endif
                
              
              </div>
                <div class="wishlist-action">
                <form
                    action=""
                    method="post" class="formAddToCart">
                    @csrf
                    <input type="hidden" name="id_product"
                            value="{{$product -> id}}">
                  <a class="btn btn-cart font-sm-bold add-to-cart cart-addition" href="#"    data-product-id="{{$product -> id}}" data-product-url="{{route('site.cart.add','outer')}}" data-button-action="add-to-cart">Add to Cart</a>
                  </form>
                </div>
                <div class="wishlist-remove"><a class="btn btn-delete removeFromWishlist" data-product-id="{{$product -> id}}" href="{{route('wishlist.destroy',$product -> id)}}"></a></div>
              </div>
          @endforeach
           @endisset
            </div>



          </div>