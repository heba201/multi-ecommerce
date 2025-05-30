
<?php
            if(app()->getLocale() == 'ar'){
                $path=asset('front/assets/css/stylertl.css?v=2.1');
            }else{
                $path=asset('front/assets/css/style.css?v=3.0.0');
            }
            ?>
               <link href="{{$path}}" rel="stylesheet">
               <link href="{{asset('front/assets/css/css_aiz-core.css')}}" rel="stylesheet">
              
               <link href="{{asset('front/assets/css/plugins/slick.css')}}" rel="stylesheet">

               <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

         
              
               <link href="{{asset('front/assets/css/product_details_style.css')}}" rel="stylesheet">

<style>
  .list-colors li {
    border:0 !important;
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
    border: 2px dashed #444;
}

.radio-toolbar input[type="radio"]:checked + label {
    background-color: #ffffff;
    border-color: #fd9636;
}

/********************** */
.zooma-main {
  overflow: hidden;
  position: relative;
  max-width: 500px;
  max-height: 500px;
}
.zooma-main img {
  pointer-events: none;
  display: block;
  width: 100%;
  height: auto;
  cursor: zoom-in;
  opacity: 0;
  top: 0;
  left: 0;
}
.zooma-main img.is-loaded {
  position: absolute;
}
.zooma-main img.is-active {
  opacity: 1;
  pointer-events: initial;
}
.zooma-main img.is-zoomed-in {
  cursor: zoom-out;
  width: initial;
}

.zooma-thumbnail img {
  display: block;
  width: 80px;
  height: auto;
  opacity: 0.5;
  padding: 10px;
}
.zooma-thumbnail img.is-active {
  opacity: 1;
  outline: 1px solid;
}

*,
*::before,
*::after {
  box-sizing: border-box;
}

.wrapper {
  width: 1200px;
  margin: 0 auto;
}

.product {
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
}
.product__thumbnails {
  flex-shrink: 0;
}
.product__focus {
  margin: 0 40px;
}
.product__description {
  width: 30%;
}
/* ******************** */
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
</style>



<div class="getmodal modal fade quickview-modal-product-details ModalQuickview" id="ModalQuickview" tabindex="-1" aria-hidden="true" style="display: none;">
       
<div class="modal-dialog modal-xl">
          <div class="modal-content apply-job-form">
                        
       

            <button class="btn-close" type="button" data-bs-dismiss="modal" onclick="del()"   aria-label="Close"></button>
            <div class="modal-body p-30">
              <h2></h2>

         

              <div class="row">
                
              <div class="col-lg-6">
                  <div class="gallery-image">
                    <div class="galleries-2">
                      <div class="detail-gallery">
                        <div class="product-image-slider-2">
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
                        
                            <!-- card left -->
                <div class="product-imgs">
                  <div class="img-display">
                     
                    
                @if($product->video_link !="")
                <br>
                      <iframe width="100%" id="pframe" class="youtube-video" height="300" src="{{getYoutubeEmbedUrl($product->video_link)}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                      @endif
                    <div class="img-showcase">
                      <?php
                       if(count($images_arr) > 0){
                        foreach(array_values(array_unique($images_arr))  as $img){
                        ?>
                      <img src="{{$img}}" alt="product image" />
                      
                      <?php
                       }
                      }
                      ?>
                    </div>
                  </div>
                  <div class="img-select">
                    <?php
                    $i=0;
                  if(count($images_arr) > 0){
                        foreach(array_values(array_unique($images_arr))  as $img){
                          $i++;
                        ?>
                  <div class="item-thumb" style="padding:10px">
                      <a href="#" data-id="{{$i}}">
                        <img src="{{$img}}" alt="product image"/>
                      </a>
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
                  </div>
                  <div class="box-tags">
                    <div class="d-inline-block mr-25"><span class="font-sm font-medium color-gray-900">{{tran('Category')}} : </span><a class="link" href="{{route('category',App\Models\Category::where('id',$product->category_id)->first()->slug)}}">{{App\Models\Category::where('id',$product->category_id)->first()->name;}}</a></div>
                    <?php
                    if($product->tags !=""){
                      $tags_arr=explode(",",$product->tags);
                    ?>
                    <div class="d-inline-block"><span class="font-sm font-medium color-gray-900">{{tran('Tags')}} : </span>
                  <?php
                  for($i=0;$i<count($tags_arr);$i++){?>
                    <a class="link" href="#">{{$tags_arr[$i]}} </a><?php echo $i < count($tags_arr)-1 ? ',' :'' ;?>

                    <?php
                  }
                  ?>
                  </div>
                  <?php
                    }
                    ?>
                  </div>
                </div>


                <div class="col-lg-6">
                  <div class="product-info">
                    <h5 class="mb-15">{{$product -> name}}</h5>
                    <?php
                    $total = 0;
                    $total += $product->reviews->count();
                    $discount_precentage=0;
                    if(home_price($product) != home_discounted_price($product)){
                if(discount_in_percentage($product) > 0){
                $discount_precentage=discount_in_percentage($product);
                }
                }

                    $shop_slug="";
                    if($product->added_by=="seller"){
                    $shop=App\Models\Shop::where('user_id',$product->user->id)->first();
                    if($shop){
                      $shop_slug=$shop->slug;
                    }
                  }
                    ?>
                    <div class="info-by">@if($product->added_by=="seller" && get_setting('vendor_system_activation') == 1)<span class="bytext color-gray-500 font-xs font-medium"> {{tran('by')}} </span><a class="byAUthor color-gray-900 font-xs font-medium" href="{{route('shop.visit',$shop_slug)}}">{{$shop->name}}</a>@endif
                      <div class="rating d-inline-block">{{ render_newStarRating($total) }} <span class="font-xs color-gray-500 font-medium"> ({{ $total }} reviews)</span></div>
                    </div>
                    <div class="border-bottom pt-10 mb-20"></div>
                    <div class="box-product-price">
                      <h3 class="color-brand-3 price-main d-inline-block mr-10">{{ home_discounted_price($product) ?? home_price($product) }}</h3> @if(home_discounted_price($product) && $discount_precentage !=0)<span class="color-gray-500 price-line font-xl line-througt">{{home_price($product)}}</span>@endif
                    </div>
                    <div class="product-description mt-10 color-gray-900">
                      <ul class="list-dot">
                        <li> {!! $product -> description !!}</li>
                      </ul>
                     
                    </div>
                    @if (count(json_decode($product->colors)) > 0)
                    @php
                    $firstcolor=\App\Models\Color::where('code', json_decode($product->colors)[0])->first()->name;
                    @endphp
                   
                    <div class="box-product-color mt-10">
                      <p class="font-sm color-gray-900">Color : <span class="color-brand-2 nameColor" id="spncolor"> <input type="text"  id="color{{$product->id}}" class="selcolor" readonly style="border:none;color:#fd9636" name="color" value="{{$firstcolor}}"></span></p>
                      <ul class="list-colors">
                      @foreach (json_decode($product->colors) as $key => $color)
                      <li><input onclick="setcolor(this)" id="<?php echo \App\Models\Color::where('code', $color)->first()->name; ?>" class="input-checkbox chkc<?php echo $key==0 ?'active':'' ;?>"  type="radio" name="rdcolor[]"  value="{{$color}}" style="background-color:{{ \App\Models\Color::where('code', $color)->first()->code }}" @if ($key == 0) checked @endif></li> 
                      @endforeach
                      </ul>
                    </div>
                    @endif
                    <div class="box-product-style-size mt-10">
                      <div class="row">
                        
                @if ($product->digital == 0)
                <!-- Choice Options -->
                @if ($product->choice_options != null)
                @foreach (json_decode($product->choice_options) as $key => $choice)
                <input type="hidden" class="attribute_id{{$product->id}}" value="{{$choice->attribute_id}}">
                      <div class="col-lg-12 mb-10 radio-toolbar">
                          <p class="font-sm color-gray-900"><h6 class="color-gray-900 mt-20 mb-10">{{ \App\Models\Attribute::find($choice->attribute_id)->name }}</h6></p>
                          
                          @foreach ($choice->values as $key => $value)
                          <input type="radio" id="{{$value}}"  name="attribute_id_{{$choice->attribute_id}}"  class="attrval{{$product->id}}"  value="{{$value}}" {{ $key == 0  ? "checked":""}}>
                          <label for="{{$value}}">{{$value}}</label>
                            @endforeach
                        </div>
                        @endforeach
                        @endif
                        @endif
                      </div>
                    </div>

                    <div class="buy-product mt-5">
                      <p class="font-sm mb-10">{{tran('Quantity')}}</p>
                      <div class="box-quantity">
                        <div class="input-quantity">
                          <input class="font-xl color-brand-3" type="text" value="1"  id="quantity{{$product ->id}}"><span class="minus-cart"></span><span class="plus-cart"></span>
                        </div>
                        <div class="button-buy"><a class="btn btn-cart cart-addition" href="#" data-product-url="{{route('site.cart.add','details')}}" data-product-id="{{$product ->id}}">Add to cart</a><a class="btn btn-buy cart-addition" data-product-url="{{route('site.cart.add','details')}}" data-product-id="{{$product ->id}}" href="{{route('payment')}}">Buy now</a></div>
                      </div>
                    </div>
                  </div>
                 
                </div>
              
              </div>
            </div>
          </div>
        </div>

      </div>


      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
      
      <script src="{{asset('front/assets/js/pro_script.js')}}" ></script>  

      <script>
        function del(){
          var iframes = document.querySelectorAll('iframe');
        for (var i = 0; i < iframes.length; i++) {
            iframes[i].parentNode.removeChild(iframes[i]);
        }
        }
    
    </script> 
   




   
 

      