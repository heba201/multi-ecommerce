@if ($key == 0 || ($key+1 > 3 && (($key+1)%3) == 1))
    @php $bg = "linear-gradient(to right, #e2583e 0%, #bf1931 100%);"; @endphp
@elseif ($key == 1 || ($key+1 > 3 && (($key+1)%3) == 2))
    @php $bg = "linear-gradient(to right, #7cc4c3 0%, #479493 100%);"; @endphp
@elseif ($key == 2 || ($key+1 > 3 && (($key+1)%3) == 0))
    @php $bg = "linear-gradient(to right, #98b3d1 0%, #5f4a8b 100%);"; @endphp
@endif

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



@if($coupon->type == 'product_base')
                        <!-- Coupon Products -->
                        @php $products_el = App\Models\Product::whereIn('id', $coupon_products)->get(); @endphp
                        @foreach($products_el as $product) 
                        <?php
                    $img="";
                      if($product->thumbnail_img !=""){
                        $img=asset('assets/images/products/'.$product->thumbnail_img);
                        
                      }else{
                        if($product->images() ->count() > 0){
                        $img=$product -> images[0] -> photo ;
                        }
                    }  
                    ?>    
                      <div class="swiper-slide">
                          <div class="card-product-small"> 
                            <div class="card-image">
                                 <a href="{{ route('product.details', $product->slug) }}"> <img  height="100" src="{{$img}}" alt="Ecom"></a>
                                </div>
                          </div>
                        </div>
                        @endforeach

                        @else
                <!-- Coupon Discount range -->
                <span class="fs-12 text-white pb-lg-3 d-block m-auto ">
                    @if($coupon->discount_type == 'amount')
                        {{ tran('Min Spend ') }} <strong>{{ single_price($order_discount->min_buy) }}</strong> {{ tran('from') }} <strong>{{ $name }}</strong> {{ tran('to get') }} <strong>{{ single_price($coupon->discount) }}</strong> {{ tran('OFF on total orders') }}
                    @else 
                        {{ tran('Min Spend ') }} <strong>{{ single_price($order_discount->min_buy) }}</strong> {{ tran('from') }} <strong>{{ $name }}</strong> {{ tran('to get') }} <strong>{{ $coupon->discount }}%</strong> {{ tran('OFF on total orders') }}                                   
                    @endif
                </span>
            @endif