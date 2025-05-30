@extends('layouts.site')

@section('content')

<div class="section-box">
        <div class="breadcrumbs-div">
         
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">Home</a></li>
              <li><a class="font-xs color-gray-500" href="#">{{$brand -> name}}</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="section-box shop-template mt-30">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 order-first order-lg-last">
             
              <div class="box-filters mt-0 pb-5 border-bottom">
                <div class="row">
                <form id="search-form" class="form-search" method="GET" action="{{route('brand',$slug)}}">
                <div class="col-xl-10 col-lg-9 mb-10 text-lg-end text-center"> 
                  @if($brand_products->count() > 0)
                  @if(!isset($limit))<span class="font-sm color-gray-900 font-medium border-1-right span"><?php $current_showing=($brand_products->currentPage() - 1)*$brand_products->perPage()+1; $to_showing=($brand_products->currentpage()-1) * $brand_products->perpage() + $brand_products->count();?> Showing {{($brand_products->currentPage() - 1)*$brand_products->perPage()+1}}&ndash;{{$to_showing}} of {{$brand_products->total()}} results</span>
                  @else 
                  <span class="font-sm color-gray-900 font-medium border-1-right span">Showing {{1}}&ndash;{{$limit}} of {{$brand_products->count()}} results</span>
                  @endif
                  @endif
                    <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">Sort by:</span>
                    <div class="dropdown dropdown-sort border-1-right">
                        <select class="form-control" name="sort_by" style="margin: 0px;" id="sort" onchange="filter()">
                        <option value="">{{ trans('Sort by')}}</option>
                          <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @ @else selected @endisset><a class="dropdown-item active" href="#">Latest products</a></option>
                          <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset><a class="dropdown-item" href="#">Oldest products</a></option>
                          <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset><a class="dropdown-item" href="#">Price low to high</a></option>
                          <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset><a class="dropdown-item" href="#">Price high to low</a></option>
                        </select>
                       
                      </div> 
                    </div>

                    <div class="d-inline-block">
                      <div class="dropdown dropdown-sort">
                        <select class="form-control" name="limit" style="margin: 0px;" onchange="filter()">
                        <option value="">{{ trans('Show')}}</option>
                        <option value="30" @isset($limit) @if ($limit == '30') selected @endif @endisset><a class="dropdown-item active" href="#">30 items</a></option>
                        <option value="50" @isset($limit) @if ($limit == '50') selected @endif @endisset><a class="dropdown-item" href="#">50 items</a></option>
                        <option value="100" @isset($limit) @if ($limit == '100') selected @endif @endisset><a class="dropdown-item" href="#">100 items</a></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
              <div class="row mt-20">
              @isset($brand_products)
              @foreach($brand_products as $product) 

              @php
                $discount_precentage=0;
                $total = 0;
                $total += $product->reviews->count();
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
                @endphp
              <?php
                     $shop_slug="";
                    $shop=App\Models\Shop::where('user_id',$product->user->id)->first();
                    if($shop){
                      $shop_slug=$shop->slug;
                    }
                    ?>  
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card-grid-style-3">
                    <div class="card-grid-inner">
                      <div class="tools"><a class="btn btn-wishlist btn-tooltip mb-10 addToWishlist wishlistProd{{$product -> id}}" href="#" data-product-id="{{$product -> id}}" aria-label="Add To Wishlist"></a><a class="btn btn-compare btn-tooltip mb-10" href="" data-product-id="{{$product -> id}}"  data-product-url="{{route('compare.addToCompare')}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view"  data-product-id="{{$product->id}}" data-product-url="{{route('product.quickview',$product->id)}}"></a></div>
                      <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{$discount_precentage}}%</span>@endif<a href="{{route('product.details',$product -> slug)}}"><img src="{{$img}}" alt="Ecom"></a></div>
                      <div class="info-right">@if($product->added_by=="seller")<a class="font-xs color-gray-500" href="{{route('shop.visit',$shop_slug)}}">{{trans('Vendor').' '.$shop->name}}</a>@else <span class="font-xs color-gray-500" >{{trans('In House product')}}</span>@endif<br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$product -> slug)}}">{{$product -> name}}</a>
                        <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>
                        <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($product) ?? home_price($product) }}</strong> @if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($product)}}</span>@endif</div>
                        <div class="mt-20 box-btn-cart">
                     
                        <input type="hidden" value="1" id="quantity{{$product->id}}"> <a class="btn btn-cart cart-addition" data-product-id="{{$product -> id}}"  data-product-url="{{route('site.cart.add','outer')}}" data-product-slug="{{$product -> slug}}" href="#" data-button-action="add-to-cart">Add To Cart</a>
                        
                        </div>
                       <hr>
                        <ul class="list-features">
                        <li> <?php echo (strlen($product->description) <  40) ? $product->description : strip_tags(substr($product->description,0,40)).'...' ;?></li> 
                        </ul> 
                      </div>
                    </div>
                  </div>
                </div>
                 
                @endforeach
                @endisset
              
              </div>
              <nav>
                <ul class="pagination">
                @if(!isset($limit))
                {!! $brand_products->links() !!}
                @endif
                </ul>
              </nav>
            </div>
           
           
            @include('front.includes.products_catgeroies_side')
            
         
          </div>
        </div>
      </div>
       
@push('js')
<script>  
        function filter(){
            $('#search-form').submit();
        }
    </script>
@endpush
@endsection