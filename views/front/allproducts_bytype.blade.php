@extends('layouts.site')

@section('content')
<div class="section-box">
  @php
  $title="";
            if ($type == 'best_sellings'){
             $title= tran('Top Selling Products');
                 }
                         
                        elseif ($type == 'featured_products'){
                          $title=  tran('Featured Products');
                        }
                          
                            elseif ($type == 'most_viewedproducts'){
                              $title= tran('Most Viewed Products');
                            }
                         
                            elseif ($type == 'trending_products'){
                              $title= tran('Trending Products');
                            }
                         
                            elseif ($type == 'trending_best_sellings'){
                              $title= tran('Trending Best Selling Products');
                            }
                       
                            elseif ($type == 'trending_most_viewedproducts'){
                              $title= tran('Trending Most Viewed Products');
                            }
                         
                            elseif ($type == 'trending_topbrand_products'){
                              $title=tran('Trending Top Brand Products');
                            }
                            elseif ($type == 'todays_deal_products'){
                              $title=tran('Todays Deal Products');
                            }
                            
                            elseif ($type == 'new_arrivals_products'){
                              $title=tran('New Arrival Products');
                            }
                        @endphp
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="{{route('products.view_all',$type)}}">{{$title}}</a></li>
            </ul>
          </div>
        </div>
      </div>  
           <section class="mb-3 mt-3 pt-50" id="section_types">
        <div class="container">
    <!-- Top Section -->
    <div class="d-flex mb-4 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-3 mb-sm-0">
                    <span class="pb-3">
                    {{$title}}
                    </span>

                  
                </h3>
            </div>

     
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

                    
                <div class="pagination">
                    {{ $products->links() }}
                </div>

                </div>


                </div>

                

              </section>
               

@endsection