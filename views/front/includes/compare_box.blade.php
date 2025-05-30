<div class="box-compare-products" id="box-compare-products">
                <div class="table-responsive">
                @if(Session::has('compare'))
                    @if(count(Session::get('compare')) > 0)
                    <a class="btn btn-buy w-auto" href="{{route('compare.reset')}}">{{tran('Empty Compare List')}}</a><br><br>
                    <table>
                    <tbody>
                      <tr>
                        <td><span>{{tran('Products')}}</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                                   

                                    <?php
                   $img="";
                   $product = \App\Models\Product::find($item);
                    if($product->thumbnail_img !=""){
                      $img=asset('assets/images/products/'.$product->thumbnail_img);
                    }else{
                     if($product -> images()->count() > 0){
                      $img= $product -> images[0] -> photo ;
                     }
                    }
                    
                    ?>
                        <td><img src="{{$img}}" alt="Ecom">
                          <h6><a class="text-brand-3" href="{{route('product.details',$product->slug)}}"> {{ $product->name }}</a></h6>
                        </td>
                
                        @endforeach

                      </tr>
                      
                      
                      <tr>
                        <td><span>Review</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                        <?php
                           $product = \App\Models\Product::find($item);
                            $total = 0;
                            $total += $product->reviews->count();
                        ?>
                        <td>
                          <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500"> ({{ $total }})</span></div>
                        </td>
                        
                        @endforeach
                      </tr>
                     
                      
                      <tr>
                      <td><span>Product Category</span></td>
                      @foreach (Session::get('compare') as $key => $item)
                      <?php
                      $product = \App\Models\Product::find($item);
                      ?>
                        <td><span>{{$product ->category->name}}</span></td>
                        @endforeach
                      </tr>
                     
                      <tr>
                        <td><span>Price</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                      <?php
                       $discount_precentage=0;
                      $product = \App\Models\Product::find($item);
                      if(discount_in_percentage($product) > 0){
                        $discount_precentage=discount_in_percentage($product);
                        }

                    
                      ?>
                        <td><span class="font-sm-bold color-brand-3">{{ home_discounted_price($product) ?? home_price($product) }}</span>@if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">&nbsp;{{home_price($product)}}</span>@endif</td>
                        @endforeach
                      </tr>
                      <tr>
                        <td><span>{{tran('Stock status')}}</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                           @php
                           $product = \App\Models\Product::find($item);  
                    $qty = 0;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                    @endphp
                        <td>
                        @if($product->stock_visibility_state == 'quantity')  
                        <span class="btn btn-gray font-sm-bold">({{ $qty .' ' .tran('available') }})</span>
                        @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                        <span class="btn btn-gray font-sm-bold">{{tran('in stock')}}</span>
                        @elseif($product->stock_visibility_state == 'text' && $qty < 1)
                        <span class="btn btn-gray font-sm-bold">{{tran('out of stock')}}</span>
                        @endif
                      </td>
                        @endforeach
                      </tr>
                      <tr>
                     
                        <td><span>Buy now</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                        <?php
                           $product = \App\Models\Product::find($item); 
                        ?>
                        <td><a class="btn btn-buy w-auto cart-addition" href="#" data-product-url="{{route('site.cart.add','outer')}}"  data-product-id="{{$product -> id}}">Add to Cart</a></td>
                        @endforeach
                      </tr>
                      <tr>
                        <td><span>Remove</span></td>
                        @foreach (Session::get('compare') as $key => $item)
                        <?php
                           $product = \App\Models\Product::find($item); 
                        ?>
                        <td><a class="btn btn-delete delete-compare"  data-product-url="{{route('compare.delete')}}" data-product-id="{{$product -> id}}"></a></td>
                        @endforeach
                      </tr>
                    </tbody>
                  </table>
                
                  @endif
                  @else 
                  <center><h4 class="color-brand-3">{{tran('Your comparison list is empty')}}</h4></center>
                  @endif
                </div>
              </div>