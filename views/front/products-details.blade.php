@extends('layouts.site')
<?php    $product=$data['product'];      ?>
@section('meta_title'){{ $product->meta_title }}@stop

@section('meta_description'){{ $product->meta_description }}@stop

@section('meta_keywords'){{ $product->tags }}@stop


@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $product->meta_title }}">
    <meta itemprop="description" content="{{ $product->meta_description }}">
    <meta itemprop="image" content="{{ asset($product->meta_img) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $product->meta_title }}">
    <meta name="twitter:description" content="{{ $product->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset('assets/images/products/'.$product->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($product->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $product->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product.details', $product->slug) }}" />
    <meta property="og:image" content="{{ asset('assets/images/products/'.$product->meta_img) }}" />
    <meta property="og:description" content="{{ $product->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($product->unit_price) }}" />
    <meta property="product:price:currency"
        content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
<style>

input[type="radio"]:checked{
  background-image: url({{asset('assets/imgs/page/shop/check.svg')}});
    background-repeat: no-repeat;
    background-position: center;
    transition: 0.5s;
}
.checkedcls {
    /* background-color: #253849; */
    background-image: url({{asset('assets/imgs/page/shop/check.svg')}});
    background-repeat: no-repeat;
    background-position: center;
    transition: 0.5s;
}

.input-checkbox{
    position: relative;
    margin-left: 4px;
    width: calc(3em - 4px);
    height: calc(3em - 4px);
    float: left;
    margin: 4px 10px 2px 1px;
    border-radius: 50%;
    vertical-align: middle;
    /* border: 5px solid #295282; */
    -webkit-appearance: none;
    outline: none;
    cursor: pointer;
    transition: 0.5s;
}



.radio-toolbar {
  margin: 10px;
}

.radio-toolbar input[type="radio"] {
  opacity: 0;
  position: fixed;
  width: 0;
}

.radio-toolbar label {
    display: inline-block;
    background-color: #ffffff;
    padding: 10px 20px;
    font-family: sans-serif, Arial;
    font-size: 16px;
    border: 2px solid #425a8b;
    border-radius: 4px;
}

.radio-toolbar label:hover {
  background-color: #dfd;
}

.radio-toolbar input[type="radio"]:focus + label {
    /* border: 2px dashed #444; */
}

.radio-toolbar input[type="radio"]:checked + label {
    background-color: #ffffff;
    border-color: #fd9636;
}
.close {
 background-color:transparent !important;
 border:none !important;
 font-size: 18px;
}
.close span{
  /* color: #fff */
  }
</style>


      <div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="{{route('category',App\Models\Category::where('id',$product->category_id)->first()->slug)}}">{{App\Models\Category::where('id',$product->category_id)->first()->name}}</a></li>
            </ul>
          </div>
        </div>
      </div>
      
      <section class="section-box shop-template">
        
      <div class="container">
      <form  class="boxed">
             @csrf
          <div class="row">
            <div class="col-lg-5">
              <div class="gallery-image" dir="ltr">
                <div class="galleries">
                  <div class="detail-gallery">
                  @php
                  $discount_precentage=0;
                
                if(home_price($product) != home_discounted_price($product)){
                if(discount_in_percentage($product) > 0){
                $discount_precentage=discount_in_percentage($product);
                }
                }
                $product->viewed=$product->viewed+1;
                        $product->save(); 
               
                @endphp
                @if($discount_precentage !=0)<label class="label">-{{$discount_precentage}}%</label>@endif
                    <div class="product-image-slider">
                      @if($product->video_link !="")
                    <figure class="border-radius-10">
                    <iframe width="100%" height="50%" src="{{getYoutubeEmbedUrl($product->video_link)}}"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </figure>
                    @endif
                     <?php
                      $images_arr=[];
                  if($product->thumbnail_img !=null){
                    $images_arr[]= asset('assets/images/products/'.$product->thumbnail_img);
                   
                  }
                  if($product -> images ->count() > 0){
                  foreach($product -> images as $img){
                    $images_arr[]= asset($img->photo);
                  }
                    
                  }

                  if($product -> stocks ->count() > 0){
                    foreach($product -> stocks as $img){
                     if($img->image !=""){
                        $images_arr[]= $img->image;
                     }
                    }
                  }
                      ?>
                    @if(count($images_arr) > 0)
                    @foreach(array_values(array_unique($images_arr))  as $img)
                    <figure class="border-radius-10">
                      <img src="{{$img}}" alt="product image" >
                    </figure>
                    @endforeach
                    @endif
                   
                    </div>
                  </div>
                 
                  @if(count($images_arr) > 0)
                  <div class="slider-nav-thumbnails">
                  @if($product->video_link !="")
                    <div>
                      <div class="item-thumb">
                        <img src="http://img.youtube.com/vi/<?php echo getYoutubeEmbedUrl($product->video_link,true);?>/mqdefault.jpg" alt="product image">
                      </div>
                    </div>
                  @endif
                  @foreach(array_values(array_unique($images_arr))  as $img)
                  <div>
                      <div class="item-thumb">
                        <img src="{{$img}}" alt="product image">
                      </div>
                    </div>
                    @endforeach 
                  </div>
                  @endif
                
                </div>
              </div>
            </div>

            @php
                    $total = 0;
                    $total += $product->reviews->count();
                    $shop_slug="";
                    $shop=App\Models\Shop::where('user_id',$product->user->id)->first();
                    if($shop){
                      $shop_slug=$shop->slug;
                    }
                @endphp
          
            <div class="col-lg-7">
              <h3 class="color-brand-3 mb-25">{{$product -> name}}</h3>
              <div class="row align-items-center">
                <div class="col-lg-4 col-md-4 col-sm-3 mb-mobile"><span class="bytext color-gray-500 font-xs font-medium">@if($product->added_by=="seller" && get_setting('vendor_system_activation') == 1) {{tran('by')}} </span> <a class="byAUthor color-gray-900 font-xs font-medium" href="{{route('shop.visit',$shop_slug)}}">{{$shop->name}}</a>@endif
                  <div class="rating mt-5">
                
                <span class="rating rating-mr-1"> {{ render_newStarRating($total) }} </span>
                    <span class="font-xs color-gray-500 font-medium"> ({{ $total }} reviews)</span></div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-9 text-start text-sm-end">
                  <a class="mr-20 addToWishlist wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  href="#" data-product-id="{{$product -> id}}"><span class="btn btn-wishlist mr-5 opacity-100 transform-none"></span><span class="font-md color-gray-900">Add to Wish list</span></a>
                  <a href=""   class="btn-compare" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$product -> id}}"><span class="btn btn-compare mr-5 opacity-100 transform-none"></span><span class="font-md color-gray-900">Add to Compare</span></a>
                </div>

                
              </div>
              <div class="border-bottom pt-10 mb-20"></div>
              @if (home_price($product) != home_discounted_price($product))
              <div class="box-product-price">
                <h3 class="color-brand-3 price-main d-inline-block mr-10">
                  
                {{ home_discounted_price($product) ?? home_price($product) }}</h3>@if(home_discounted_price($product))
                <span class="color-gray-500 price-line font-xl line-througt">{{home_price($product)}}</span>  @endif
              </div>
              @else
              <div class="box-product-price">
                <h3 class="color-brand-3 price-main d-inline-block mr-10">
                  
                {{home_discounted_price($product) }}</h3>
              </div>
              @endif
             
              <div class="product-description mt-20 color-gray-900">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php echo $product->description ;?>
                  </div>
                </div>
              </div>


        <!-- Color Options -->
      
              @if (count(json_decode($product->colors)) > 0)
               
              <h6 class="color-gray-900 mt-20 mb-10">Color : <input type="text"  class="selcolor" name="color" id="color{{$product->id}}" readonly style="border:none;color:#fd9636"> </h6>  
                <ul class="list-color col-lg-5">
                @foreach (json_decode($product->colors) as $key => $color)
                      <li><input onclick="setcolor(this)" id="<?php echo \App\Models\Color::where('code', $color)->first()->name; ?>" class="input-checkbox chkc<?php echo $key==0 ?'active':'' ;?>"  type="radio" name="rdcolor[]"  value="{{$color}}" style="background-color:{{ \App\Models\Color::where('code', $color)->first()->code }}"  {{ $key == 0  ? "checked":""}}></li> 
                      @endforeach
                    
                    </ul>
              @endif
              <div class="row">
              @if ($product->digital == 0)
                <!-- Choice Options -->
                @if ($product->choice_options != null)
                @foreach (json_decode($product->choice_options) as $key => $choice)
                <input type="hidden" class="attribute_id{{$product->id}}" value="{{$choice->attribute_id}}">
                      <div class="col-lg-12 mb-10 radio-toolbar">
                          <p class="font-sm color-gray-900">  <h6 class="color-gray-900 mt-20 mb-10">{{ \App\Models\Attribute::find($choice->attribute_id)->name }}</h6></p>
                          
                          @foreach ($choice->values as $key => $value)
                          <input type="radio" id="{{$value}}"  name="attribute_id_{{$choice->attribute_id}}" class="attrval{{$product->id}}" value="{{$value}}" {{ $key == 0  ? "checked":""}}>
                          <label for="{{$value}}">{{$value}}</label>
                            @endforeach
                        </div>
                        @endforeach
                        @endif
                        @endif
                      </div>
          

              @if (count($product->stocks) > 0)
             <div class="box-product-color mt-20">
                <ul class="list-colors">
                @foreach($product -> stocks as $stock)
                @if($stock ->image !="")
                  <li class="enabled"><img src="{{$stock->image}}" alt="Ecom" title="{{$stock->image}}"></li>
                  @endif
                  @endforeach
                </ul>
              </div>
              @endif

            
              <div class="buy-product mt-30">
                <p class="font-sm mb-20">{{tran('Quantity')}}</p>
                <div class="box-quantity">
                  <div class="input-quantity">
                    <input class="font-xl color-brand-3" type="text" value="1" id="quantity{{$product ->id}}" name="quantity"><span class="minus-cart"></span><span class="plus-cart"></span>
                  </div>
                 
                  
                  <div class="button-buy"><a class="btn btn-cart cart-addition" href="#" data-product-url="{{route('site.cart.add','details')}}" data-product-id="{{$product ->id}}">Add to cart</a><a class="btn btn-buy cart-addition"  data-product-url="{{route('site.cart.add','details')}}" data-product-id="{{$product ->id}}" data-product-btn="buynow" href="{{route('site.cart.index')}}">Buy now</a></div>
                    
                </div>
               
              </div>

              <div class="info-product mt-40">
                <div class="row align-items-end">
                  <div class="col-lg-4 col-md-4 mb-20">{{tran('Category')}} : <span class="color-gray-500">{{App\Models\Category::where('id',$product->category_id)->first()->name;}}</span><br>{{tran('Tags')}} : <span class="color-gray-500">{{$product->tags}}</span></span></div>
                  <div class="col-lg-4 col-md-4 mb-20"><span class="font-sm font-medium color-gray-900"><br><span class="color-gray-500"></span><br><span class="color-gray-500"></span></span></div>

                  <div class="col-lg-4 col-md-4 mb-20"></span>
                  <div class="col-lg-4 col-md-4 mb-20 text-start text-md-end">
                    <div class="d-inline-block">
                      <div class="share-link"><span class="font-md-bold color-brand-3 mr-15">{{tran('Share')}}</span><a class="facebook hover-up" href="https://www.facebook.com/sharer/sharer.php?u={{route('product.details',$product->slug)}}&display=popup""></a><a class="printest hover-up" href="http://pinterest.com/pin/create/bookmarklet/?url=http://www.islandcompany.com/<?php echo route('product.details',$product->slug) ; ?>"></a><a class="twitter hover-up" href="https://twitter.com/intent/tweet?url=<?php echo route('product.details',$product->slug) ;?>"></a><a class="instagram hover-up" href="https://www.instagram.com/?url={{route('product.details',$product->slug)}}"></a></div>
                    </div>

                    

                  </div>
                </div>
              </div>
            </div>

          </div>
          </form>
          <div class="border-bottom pt-30 mb-40"></div>
          
          <section class="section-box">
        <div class="container">
          <ul class="list-col-5">
          <li>
              <div class="item-list">
                  <p class="font-sm color-gray-500"><a  href="javascript:void(0);"  onclick="product_review({{$product ->id}})"> <i class="fa-solid fa-star"></i> {{trans('Review This Product')}}</a></p>
              
              </div>
            </li>
            @if (get_setting('conversation_system') == 1)
            <li>
              <div class="item-list">
                  <p class="font-sm color-gray-500"><a  href="javascript:void(0);"  onclick="show_chat_modal()">  <i class="fa-solid fa-message"></i> {{trans('Message the seller')}}</a></p>
              
              </div>
            </li>
            @endif
           
            @if(get_setting('product_query_activation') == 1)
            <li>
              <div class="item-list">
                  <p class="font-sm color-gray-500"> <a href="javascript:void(0);"  onclick="show_productinquiry_modal()"> <i class="fa-solid fa-question"></i> {{trans('Product Inquiries')}}</a></p>
              
              </div>
            </li>
            @endif

                  </ul>

        </div>
        </div>
        </section>
      </section>
      <section class="section-box shop-template">
        <div class="container">
          <div class="mb-10">
            <ul class="nav nav-tabs nav-tabs-product" role="tablist">
              <li><a class="active" href="#tab-description" data-bs-toggle="tab" role="tab" aria-controls="tab-description" aria-selected="true">{{tran('Description')}}</a></li>
               
              <li><a href="#tab-reviews" data-bs-toggle="tab" role="tab" aria-controls="tab-reviews" aria-selected="true">{{tran('Reviews')}} ({{$total}})</a></li>
              @if(($product->added_by=="admin")||($product->added_by=="seller" && get_setting('vendor_system_activation') == 1))
              <li><a href="#tab-vendor" data-bs-toggle="tab" role="tab" aria-controls="tab-vendor" aria-selected="true">{{tran('Seller')}}</a></li>
              @endif
              @if(get_setting('product_query_activation') == 1)
              <li><a href="#tab-productquery" data-bs-toggle="tab" role="tab" aria-controls="tab-productquery" aria-selected="true">{{tran('Product Inquiries')}}</a></li>
              @endif
              
              <li><a href="#tab-pdfspecification" data-bs-toggle="tab" role="tab" aria-controls="tab-pdfspecification" aria-selected="true">{{tran('PDF Specification')}}</a></li>

            </ul>
            <div class="tab-content">
              <div class="tab-pane fade active show" id="tab-description" role="tabpanel" aria-labelledby="tab-description">
                <div class="display-text-short">
                  <p><?php echo $product ->description ;?></p>
                </div>
              </div>
             
              <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews">
                <div class="comments-area">
                  <div class="row">
                    <div class="col-lg-4">
                      <h4 class="mb-30 title-question">{{tran("Customer reviews")}}</h4>
                      <div class="d-flex mb-30">
                        <div class="d-inline-block mr-15">
                          <div>{{render_newStarRating($total)}}</div>
                        </div>
                      </div>
                      <?php
                      $full_percent=0; $four_percent=0;$three_percent=0; $two_percent=0; $one_percent=0;
                      $pro_rating=\App\Models\Review::where('product_id',$product->id)->get()->count();
                      if($pro_rating > 0){
                      $full_percent= (\App\Models\Review::where('product_id',$product->id)->where('rating',5)->get()->count()/$pro_rating)*100;
                      $four_percent= (\App\Models\Review::where('product_id',$product->id)->where('rating',4)->get()->count()/$pro_rating)*100;
                      $three_percent= (\App\Models\Review::where('product_id',$product->id)->where('rating',3)->get()->count()/$pro_rating)*100;
                      $two_percent= (\App\Models\Review::where('product_id',$product->id)->where('rating',2)->get()->count()/$pro_rating)*100;
                      $one_percent= (\App\Models\Review::where('product_id',$product->id)->where('rating',1)->get()->count()/$pro_rating)*100;          
                      }
                     ?>
                      <div class="progress"><span>5 star</span>
                        <div class="progress-bar" role="progressbar" style="width: {{$full_percent}}%" aria-valuenow="{{$full_percent}}" aria-valuemin="0" aria-valuemax="100">{{$full_percent}}%</div>
                      </div>
                      <div class="progress"><span>4 star</span>
                        <div class="progress-bar" role="progressbar" style="width: {{$four_percent}}%" aria-valuenow="{{$four_percent}}" aria-valuemin="0" aria-valuemax="100">{{$four_percent}}%</div>
                      </div>
                      <div class="progress"><span>3 star</span>
                        <div class="progress-bar" role="progressbar" style="width: {{$three_percent}}%" aria-valuenow="{{$three_percent}}" aria-valuemin="0" aria-valuemax="100">{{$three_percent}}%</div>
                      </div>
                      <div class="progress"><span>2 star</span>
                        <div class="progress-bar" role="progressbar" style="width:{{$two_percent}}%" aria-valuenow="{{$two_percent}}" aria-valuemin="0" aria-valuemax="100">{{$two_percent}}%</div>
                      </div>
                      <div class="progress mb-30"><span>1 star</span>
                        <div class="progress-bar" role="progressbar" style="width: {{$one_percent}}%" aria-valuenow="{{$one_percent}}" aria-valuemin="0" aria-valuemax="100">{{$one_percent}}%</div>
                      </div><a class="font-xs text-muted" href="#"></a>
                    <!-- Reviews -->
    @include('front.includes.product_details.reviews')
                   
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-vendor" role="tabpanel" aria-labelledby="tab-vendor">
                <div class="vendor-logo d-flex mb-30"><img src="assets/imgs/page/product/futur.png" alt="">
                  <div class="vendor-name ml-15">
            
            <?php
            if ($product->added_by == 'seller'){
              if($product->user->shop != null){?>
            <h6><a href="{{ route('shop.visit', $product->user->shop->slug) }}">{{ $product->user->shop->name }}</a></h6>
              <?php
              }
            }else{?>
            <p class="mb-0 fs-14 fw-700">{{ tran('In House Product') }}</p>
           <?php
            }
            ?>
            
            
            @if ($product->added_by == 'seller' && $product->user->shop != null)
                    <div class="product-rate-cover text-end">
                      <div class="d-inline-block">
                        <?php
                        if(isset($product->user->shop->rating)){
                          $shoprating=$product->user->shop->rating;
                        }else{
                          $shoprating=0;
                        }

                        if(isset($product->user->shop->num_of_reviews)){
                          $shopreviews=$product->user->shop->num_of_reviews;
                        }else{
                          $shopreviews=0;
                        }
                        ?> 
                        <div  style="width: 100%">{{ render_newStarRating($shoprating) }}</div>
                      </div><span class="font-small ml-5 text-muted"> ({{$shopreviews}})</span>
                    </div>
                    @endif
                  </div>
                </div>
                <?php
                if(isset($product->user->shop->address)){
                  $shopaddress=$product->user->shop->address;
                }else{
                  $shopaddress="";
                }

                if(isset($product->user->shop->phone)){
                  $shopphone=$product->user->shop->phone;
                }else{
                  $shopphone="";
                }
                ?>
                 @if ($product->added_by == 'seller')
                <ul class="contact-infor mb-50">
                  <li><img src="assets/imgs/page/product/icon-location.svg" alt=""><strong>{{tran("Address")}}:</strong><span>{{ $shopaddress }}</span></li>
                  <li><img src="assets/imgs/page/product/icon-contact.svg" alt=""><strong>{{tran("Contact Seller")}}:</strong><span>{{ $shopphone }}</span></li>
                </ul>
               
                <div class="mt-3">
                <a href="{{ route('shop.visit', $product->user->shop->slug) }}" class="btn btn-block btn-slide-warning fs-14 fw-700 rounded-0">Visit Store</a>
            </div>
            @endif
              </div>

              @if(get_setting('product_query_activation') == 1)
              <div class="tab-pane fade" id="tab-productquery" role="tabpanel" aria-labelledby="tab-productquery">
                <div class="comments-area">
                  <div class="row">
                    <div class="col-lg-8">
                      <h4 class="mb-30 title-question">{{tran('Customer questions')}} &amp; {{tran('answers')}}</h4>
                      <div class="comment-list">
                      <?php
                      if(isset(Auth::user()->product_queries)){
                $own_product_queries = Auth::user()->product_queries->where('product_id',$product->id);
                     
                if($own_product_queries->count() > 0){?>
                <div class="py-3">
                        <h3 class="fs-16 fw-700 mb-0">
                            <span class="mr-4">{{ tran('My Questions') }}</span>
                        </h3>
                    </div>
                    <?php
                foreach($own_product_queries as $product_query){?>
                      <div class="single-comment justify-content-between d-flex mb-30 hover-up">
                          <div class="user justify-content-between d-flex">
                            <div class="thumb text-center"><img src="{{ $product_query->user->avatar_original !='' ? asset('assets/images/users/'.$product_query->user->avatar_original) : asset('assets/images/users/avatar.png') }}" alt="Ecom"><a class="font-heading text-brand" href="#">{{ $product_query->user->name }}</a></div>
                            <div class="desc">
                              <div class="d-flex justify-content-between mb-10">
                                <div class="d-flex align-items-center">
                                  <span class="font-xs color-gray-700">{{$product_query->created_at}}</span>
                                </div>
                              </div>
                              <p class="mb-10 font-sm color-gray-900">
                              {{ strip_tags($product_query->question) }}
                              </p>
                            </div>
                          </div>
                        </div>
                        <?php
                           }
                          }
                      }

                ?>
                       
                         <?php
                      $product_queries = $product->productqueries;
                      if( $product_queries->count() > 0){
                        foreach($product_queries as $key=>$value){
                         ?>
                        <div class="single-comment justify-content-between d-flex mb-30 ml-30 hover-up">
                          <div class="user justify-content-between d-flex">
                            <div class="thumb text-center"><img src="{{ $value->user->avatar_original !='' ? asset('assets/images/users/'.$value->user->avatar_original) : asset('assets/images/users/avatar.png') }}" alt="Ecom"><a class="font-heading text-brand" href="#">{{ $value->user->name }}</a></div>
                            <div class="desc">
                              <div class="d-flex justify-content-between mb-10">
                                <div class="d-flex align-items-center"><span class="font-xs color-gray-700">{{$value->created_at}}</span></div>
 
                              </div>
                              <p class="mb-10 font-sm color-gray-900">
                              {{$value->question}} <br>{{$value->reply}}
                              </p>
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
              </div>
                @endif
              
                <div class="tab-pane fade" id="tab-pdfspecification" role="tabpanel" aria-labelledby="tab-pdfspecification">
                <div class="row">
                    <div class="col-lg-8">
                    @if($product->pdf !="")<a class="mb-30 title-question" target="_blank" href="{{ asset('assets/images/products/'.$product->pdf )}}" download>{{tran('Download')}}</h4>@endif
                  </div>
              </div>
              </div>
              <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ tran('Any query about this product') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form class="" action="{{ route('customer-conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="title"
                                value="{{ $product->name }}" placeholder="{{ tran('Product Name') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="url"
                                value="{{ route('product.details',$product->slug) }}" placeholder="{{ route('product.details', $product->slug) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ tran('Your Question') }}"></textarea>
                        </div>

                        <div class="mb-3">
                    <label for="formFile" class="form-label">{{ tran('Browse')}}</label>
                    <input class="form-control" type="file" name="file" id="formFile">
                    @error("file")
                   <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                    <h4><span id="status">{{ tran('Not recording')}}</span></h4>
                    <div id="controls">
                    <button id="recordButton" onclick="return false;" style="padding:10px;border: none;background: transparent"><i class="fa-solid fa-microphone fa-xl"></i></button>
                        <button id="stopButton" style="padding:10px;border: none;background: transparent"  disabled><i class="fa-solid fa-stop fa-xl" style="color:red;"></i></button>
                   <input type="hidden" name="voice" id="voice">
                   
                    </div>
                    <br>
                    <div id="audioContainer"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-bs-dismiss="modal">{{ tran('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ tran('Send') }}</button>
                    </div>
                </form>
               
            </div>
        </div>
    </div>


              <!-- Product Inquiry -->
              <div class="modal fade" id="product_inquiry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ tran('Product Inquiries') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('product-queries.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="question" required
                                placeholder="{{ tran('Your Question') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-bs-dismiss="modal">{{ tran('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ tran('Send') }}</button>
                    </div>
                </form>
               
            </div>
        </div>
    </div>



              
              <?php  $related_products=$data['related_products'] ;?>
              @if( isset($related_products) && count($related_products) > 0 )
              <div class="border-bottom pt-30 mb-50"></div>
              <h4 class="color-brand-3">{{tran("Related Products")}}</h4>
              <div class="list-products-5 mt-20">
              @foreach($related_products as $pro)
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
            $product=$pro;
              @endphp
              <div class="card-grid-style-3">
                  <div class="card-grid-inner">
                  <div class="tools">
                    <a class="btn btn-wishlist btn-tooltip mb-10 wishlist-addition"  data-product-url="{{route('wishlist.store')}}"  data-product-id="{{$pro -> id}}" aria-label="Add To Wishlist"></a>
                    <a class="btn btn-compare btn-tooltip mb-10" href="" data-product-url="{{route('compare.addToCompare')}}" data-product-id="{{$pro -> id}}" aria-label="Compare"></a><a class="btn btn-quickview btn-tooltip quickproductshow" aria-label="Quick view" data-product-id="{{$pro->id}}" data-product-url="{{route('product.quickview',$pro->id)}}"></a>
                  </div>
                    <div class="image-box">@if($discount_precentage !=0)<span class="label bg-brand-2">-{{ $discount_precentage}}%</span>@endif<a href="{{route('product.details',$pro->slug)}}"><img src="{{$img}}" alt="Ecom"></a>
                    </div>                    
                    <div class="info-right"><a class="font-xs color-gray-500" href="shop-vendor-single.html">{{$pro->brand_id ? $pro->brand()->first()->name : ''}}</a><br><a class="color-brand-3 font-sm-bold" href="{{route('product.details',$pro -> slug)}}">{{$pro -> name}}</a>
                    <div class="rating">{{ render_newStarRating($total) }}<span class="font-xs color-gray-500">({{ $total }})</span></div>                      
                    <div class="price-info"><strong class="font-lg-bold color-brand-3 price-main">{{ home_discounted_price($pro) ?? home_price($pro) }}</strong>@if(home_discounted_price($pro) && $discount_precentage !=0)<span class="color-gray-500 price-line">{{home_price($pro)}}</span>@endif</div>
                      <div class="mt-20 box-btn-cart"><a class="btn btn-cart cart-addition" href="#" data-product-url="{{route('site.cart.add','outer')}}" data-product-id="{{$pro -> id}}">Add To Cart</a></div>
                      <input type="hidden" value="1" id="quantity{{$pro -> id}}">
                      <ul class="list-features">
                        <li><?php echo $pro->description ;?></li>
                      </ul>
                    </div>
                  </div>
                </div>

               
                
                @endforeach
             
              </div>
              @endif
              <div class="border-bottom pt-20 mb-40"></div>
              
            </div>
          </div>
        </div>
      </section>
       <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>
      @push('js')
<script>  
var txtcolor = document.getElementsByClassName("chkcactive");
setcolor(txtcolor[0]);
  function setcolor(colorchk){
  $('.selcolor').val(colorchk.id);
  }
  function product_review(product_id) {
            @if (Auth::check() && isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                       
                    });
                @else
                swal({
                  text: '{{ trans("Sorry, You need to buy this product to give review.") }}',
                  icon: 'warning',
                });
                      
                @endif
            @elseif (Auth::check() && !isCustomer())
                swal({
                  text: '{{ trans("Sorry, Only customers can give review.") }}',
                  icon: 'warning',
                });
            @else
                window.location.replace('{{route("login")}}');
            @endif
        }
        function show_chat_modal() {
            @if(Auth::check())
                $('#chat_modal').modal('show');
            @else
            window.location.replace('{{route("login")}}');
            @endif
        }

        function show_productinquiry_modal(){
          @if(Auth::check())
                $('#product_inquiry').modal('show');
            @else
            window.location.replace('{{route("login")}}');
            @endif
        }
    
</script>
<script>
        // Initialize the audio context
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext = new AudioContext();
        var audioChunks = [];
        var mediaRecorder; // Declare mediaRecorder variable

        // Get the record and stop buttons
        var recordButton = document.getElementById('recordButton');
        var stopButton = document.getElementById('stopButton');

        // Add event listeners
        recordButton.addEventListener('click', startRecording);
        stopButton.addEventListener('click', stopRecording);

        // Start recording function
        function startRecording() {
            document.getElementById('audioContainer').innerHTML="";
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream); // Assign mediaRecorder variable

                    mediaRecorder.addEventListener('dataavailable', function(e) {
                        audioChunks.push(e.data);
                    });

                    mediaRecorder.addEventListener('stop', function() {
                        var audioBlob = new Blob(audioChunks, { 'type' : 'audio/wav' });

                        var formData = new FormData();
                        formData.append('audio', audioBlob, 'recording.wav');
                        formData.append('_token',"{{ csrf_token() }}");
                        $.ajax({
                            url: "{{route('customer.conversations.uploadvoice')}}",
                            type: 'POST',
                            data:formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
        displayAudio(response.url);
        console.log(response);
         $("#voice").val(response.file);
     },
        error: function(xhr, status, error) {
         console.error('Error uploading file: ' + error);
                            }
                        });

                        audioChunks = []; // Reset audioChunks array after upload
                    });

                    mediaRecorder.start();

                    // Update UI
                    recordButton.disabled = true;
                    stopButton.disabled = false;
                    document.getElementById('status').innerHTML = 'Recording...';
                })
                .catch(function(err) {
                    console.error('Error accessing microphone: ' + err);
                });
        }

        // Stop recording function
        function stopRecording() {
            mediaRecorder.stop();

            // Update UI
            recordButton.disabled = false;
            stopButton.disabled = true;
       
            document.getElementById('status').innerHTML ='';
            document.getElementById('audioContainer').innerHTML="";
        }

         // Display audio
         function displayAudio(audioPath) {
            
            var audioPlayer = document.createElement('audio');
            audioPlayer.controls = true;
            audioPlayer.src = audioPath;
            document.getElementById('audioContainer').appendChild(audioPlayer);
        }
    </script>
    @endpush
      @endsection