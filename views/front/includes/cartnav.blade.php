<div id="cart_dropdown"><div class="dropdown-cart">
                 <?php
                 $user_id="";
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
        
                 ?>
                <div class="item-cart mb-20">
                                  <div class="cart-image"><img src="{{asset('assets/images/products/'.$product -> thumbnail_img )}}" alt="Ecom"></div>
                                  <div class="cart-info"><a class="font-sm-bold color-brand-3" href="{{route('product.details',$product->slug)}}">{{$product -> name}}</a>
                                    <p><span class="color-brand-2 font-sm-bold">{{$cartItem['quantity']}} x {{cart_product_price($cartItem, $product, false)}}</span></p>
                                  </div>
                                </div>
                  <?php
                }
              }
                 ?> 
                  <div class="border-bottom pt-0 mb-15"></div>
                  <div class="cart-total">
                    <div class="row">
                      <div class="col-6 text-start"><span class="font-md-bold color-brand-3">{{tran('Total')}}</span></div>
                      <div class="col-6"><span class="font-md-bold color-brand-1">{{$total}}</span></div>
                    </div>
                    <div class="row mt-15">
                      <div class="col-6 text-start"><a class="btn btn-cart w-auto" href="{{route('site.cart.index')}}">{{tran('View cart')}}</a></div>
                      @if ($total > 0) <div class="col-6"><a class="btn btn-buy w-auto" href="{{route('payment')}}">{{tran('Checkout')}}</a></div>@endif
                    </div>
                  </div>
                </div>
                </div>