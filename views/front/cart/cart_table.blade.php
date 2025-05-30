@php
          $cart_arr=[];
          $shipping = 0;
        $product_shipping_cost = 0;
        
    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }

    $total = 0;
    if(isset($cart) && count($cart) > 0){
        foreach ($cart as $key => $cartItem){
            $product = \App\Models\Product::find($cartItem['product_id']);
            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
            $product_shipping_cost = $cartItem['shipping_cost'];  
            $shipping += $product_shipping_cost;
        }
    }
@endphp
<div class="row" id="cart_table">
            <div class="col-lg-9">
              <div class="box-carts">
                <div class="head-wishlist">
                  <div class="item-wishlist">
                    <div class="wishlist-cb">
                      <!-- <input class="cb-layout cb-all" type="checkbox"> -->
                    </div>
                    <div class="wishlist-product"><span class="font-md-bold color-brand-3">{{tran('Product')}}</span></div>
                    <div class="wishlist-price"><span class="font-md-bold color-brand-3">{{tran('Unit Price')}}</span></div>
                    <div class="wishlist-status"><span class="font-md-bold color-brand-3">{{tran('Quantity')}}</span></div>
                    <div class="wishlist-action"><span class="font-md-bold color-brand-3">{{tran('Subtotal')}}</span></div>
                    <div class="wishlist-remove"><span class="font-md-bold color-brand-3">{{tran('Remove')}}</span></div>
                  </div>
                </div>
                
                <div class="content-wishlist mb-20" >  
                <?php
                $subtotal=0;
        foreach ($cart as $key => $cartItem){
          $product = \App\Models\Product::find($cartItem['product_id']);
               $cart_arr[]=$cartItem['id'];

               $total_rating = 0;
               $total_rating += $product->reviews->count();
               ?>
         
                <div class="item-wishlist">
                    <div class="wishlist-cb">
                      <input name="cart_id{{$cartItem['id']}}"  type="hidden" value="{{$cartItem['id']}}" > 
                    <!-- <input class="cb-layout cb-select cartid" type="checkbox" value="{{$cartItem['id']}}" name="cart_id[]"> -->
                    </div>
                    <div class="wishlist-product">
                      <div class="product-wishlist">
                        <div class="product-image"><a href="{{route('product.details',$product->slug)}}"><img src="{{asset('assets/images/products/'.$product -> thumbnail_img )}}" alt="Ecom"></a></div>
                        <div class="product-info"><a href="{{route('product.details',$product->slug)}}">
                            <h6 class="color-brand-3">{{$product -> name}}</h6></a>
                          <div class="rating">{{ render_newStarRating($total_rating) }}<span class="font-xs color-gray-500"> ({{ $total_rating }})</span></div>
                        </div>
                      </div>
                    </div>
                    <div class="wishlist-price">
                      <h4 class="color-brand-3">{{ cart_product_price($cartItem, $product) }} </h4>
                    </div>
                    <div class="wishlist-status">
                      <div class="box-quantity">
                        <div class="input-quantity">
                          <input class="font-xl color-brand-3 quantity" alt="{{$cartItem['id']}}" type="text" id="quan{{$cartItem['id']}}" value="{{ $cartItem['quantity'] }}"><span class="minus-cart"></span><span class="plus-cart"></span>
                        </div>
                      </div>
                    </div>
                    <div class="wishlist-action">
    
                      <h4 id="sub{{$cartItem['id']}}" class="color-brand-3">{{cart_product_price($cartItem, $product,false) * $cartItem['quantity']}}</h4>
                    </div>
                    <div class="wishlist-remove"><a class="btn btn-delete" onclick="delete_item({{$cartItem['id']}})" ></a></div>
                  </div>

           <?php
              }
           ?>
                </div>
           
           

                <div class="row mb-40">
                  <div class="col-lg-6 col-md-6 col-sm-6-col-6"><a class="btn btn-buy w-auto arrow-back mb-10" href="{{route('home')}}">{{tran('Continue shopping')}}</a></div>
                  @if(isset($cart) && count($cart) > 0)<div class="col-lg-6 col-md-6 col-sm-6-col-6 text-md-end"><a class="btn btn-buy w-auto update-cart mb-10" onclick="update_cart();">{{tran('Update cart')}}</a></div>@endif
                </div>
                <!-- Coupon System -->
        @if (Auth::check() && get_setting('coupon_system') == 1)
                <div class="row mb-50">
                  <div class="col-lg-6 col-md-6">
                  @if(isset($cart) && count($cart) > 0)
                  <form id="apply-coupon-form" enctype="multipart/form-data">
                    @csrf
                  
                    <input type="hidden" name="owner_id" value="{{ $cart[0]['owner_id'] }}">
                  <div class="box-cart-right p-20">
                      <h5 class="font-md-bold mb-10">Apply Coupon</h5><span class="font-sm-bold mb-5 d-inline-block color-gray-500">{{tran('Using A Promo Code?')}}</span>
                      <div class="form-group d-flex">
                        <input class="form-control mr-15" type="text"  placeholder="Enter Your Coupon" name="code"  onkeydown="return event.key != 'Enter';" required>
                        <button class="btn btn-buy w-auto" id="coupon-apply">{{tran('Apply')}}</button>
                      </div>
                    </div>
                    </form>
                    @endif
                  </div>
                 
                </div>
                @endif

              </div>
            </div>
            <div class="col-lg-3">
              <div class="summary-cart">
                <div class="border-bottom mb-10">
                  <div class="row">
                    <div class="col-6"><span class="font-md-bold color-gray-500">{{tran('Subtotal')}}</span></div>
                    <div class="col-6 text-end">
                      <h4 id="subtotal_total">{{single_price($total) }}</h4>
                    </div>
                  </div>
                </div>
               
               
                <div class="mb-10">
                  <div class="row">
                    <div class="col-6"><span class="font-md-bold color-gray-500">{{tran('Total')}}</span></div>
                    <div class="col-6 text-end">
                      <h4 id="final_total">{{ single_price($shipping+$total)}}</h4>
                    </div>
                  </div>
                </div>
                @if ($total > 0)<div class="box-button"><a class="btn btn-buy" href="<?php echo (isset($cart) && count($cart) > 0) ? route('payment') :"#"?>">{{tran('Proceed To CheckOut')}}</a></div>@endif
              </div>
            </div>
          </div>

          <script>
        function update_cart(){
          var total=0;
          var cartid=<?php echo json_encode($cart_arr);?>;
         // alert(cartid);
         var qnt_arr=[];
          var qntcollection = document.getElementsByClassName("quantity");
          for(var i=0;i<qntcollection.length;i++){
            qnt_arr.push(qntcollection[i].value);
          }
          // console.log(qnt_arr);
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
                type: 'post',
                url: "{{route('site.cart.updateQuantity')}}",
                data: {
                    'cartid':cartid,
                    'qntcollection':qnt_arr,
                },
                success: function (data) {
                 for(var i=0;i<cartid.length;i++){
                  
                  // $('.sub'+cartid[i]).innerHTML =data;
                   document.getElementById("sub"+cartid[i]).innerHTML = (data.cartinfo[i].quantity*data.cartinfo[i].price);
                   document.getElementById("quan"+cartid[i]).value = data.cartinfo[i].quantity;
                   console.log(data.cartinfo[i].quantity);
                }
                document.getElementById("subtotal_total").innerHTML = data.total;
                document.getElementById("shipping").innerHTML = data.shipping;
                document.getElementById("final_total").innerHTML = data.final_total;
                $('#cart_dropdown').load(window.location.origin+'/cartnav'); 
                },
                error:function (error) {
                  console.log(error);
                }
            });
          
        }
      </script>