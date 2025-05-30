 <style>
  .colorchk{
    display: inline-block;
    width: 36px;
    height: 36px;
    border-radius: 50% important;
    margin-bottom: 5px;
}


.input-checkbox:checked {
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
          
           <div class="sidebar-border mb-40">
                <div class="sidebar-head">
                  <h6 class="color-gray-900">{{tran('Products Filter')}}</h6>
                </div>
                <div class="sidebar-content">
                  <h6 class="color-gray-900 mt-10 mb-10">{{tran('Price')}}</h6>
                  <div class="box-slider-range mt-20 mb-15">
                    <div class="row mb-20">
                      <div class="col-sm-12">
                        <div id="slider-range"></div>
                      </div>
                    </div>
                    <div class="row">
                    <div
                        id="input-slider-range"
                        data-range-value-min="@if(\App\Models\Product::where(['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1])->count() < 1) 0 @else {{ \App\Models\Product::where(['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1])->min('unit_price') }} @endif"
                        data-range-value-max="@if(\App\Models\Product::where(['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1])->count() < 1) 0 @else {{ \App\Models\Product::where(['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1])->max('unit_price') }} @endif"
                    ></div>

                    <div class="row mt-2">
                    <div class="col-lg-12">
                            <label class="lb-slider font-sm color-gray-500">{{tran('Price Range')}}:</label>
                            <span class="min-value-money font-sm color-gray-1000 ss"
                            @if ($min_price != null)
                                    data-range-value-low="{{ $min_price }}"
                                @elseif($products->min('unit_price') > 0)
                                    data-range-value-low="{{ $products->min('unit_price') }}"
                                @else
                                    data-range-value-low="0"
                                @endif
                                id="input-slider-range-value-low"
                                >
                            </span>
                        
                        
                            <label class="lb-slider font-sm font-medium color-gray-1000"></label>-
                        <span class="max-value-money font-sm font-medium color-gray-1000 ss"
                        @if ($max_price != null)
                                data-range-value-high="{{ $max_price }}"
                                @elseif($products->max('unit_price') > 0)
                                data-range-value-high="{{ $products->max('unit_price') }}"
                                @else
                              data-range-value-high="0"
                                @endif
                                id="input-slider-range-value-high"
                        ></span>
                        </div>
                    </div>
                </div>
                      <div class="col-lg-12">
                       <!-- Hidden Items -->
                       <input type="hidden" class="min-value"  name="min_price" value="">
                      <input type="hidden" class="max-value"   name="max_price" value="">
                      </div>
                    </div>
                 
                 
                  <h6 class="color-gray-900 mt-20 mb-10">{{tran('Brands')}}</h6>
                  <ul class="list-checkbox">
                  @php
                    $brand_ids = \App\Models\Product::where('user_id', $shop->user->id)->where('published', 1)->where('approved', 1)->whereNotNull('brand_id')->pluck('brand_id')->toArray();
                @endphp
                   <?php  
                  foreach(\App\Models\Brand::whereIn('id', $brand_ids)->get() as $key => $brand){
                    $bimg=asset('assets/images/default.png');
                
                    if(count(explode("brands/",$brand->logo)) > 1){
                    $bimg=$brand->logo;
                    }
                  ?>
                 <div class="col-6">
                    <label class="aiz-megabox d-block mb-3">
                        <input value="{{ $brand->slug }}" type="radio" onchange="filter()"
                            name="brand" @isset($brand_id) @if ($brand_id == $brand->id) checked @endif @endisset>
                        <span class="d-block aiz-megabox-elem rounded-0 p-3 border-transparent hov-border-primary">
                            <img src="{{  $bimg }}"
                                class="img-fit mb-2" alt="{{ $brand->name }}">
                            <span class="d-block text-center">
                                <span
                                    class="d-block fw-400 fs-14">{{ $brand->name }}</span>
                            </span>
                        </span>
                    </label>
                  </div>
                    <?php
                    
                  }
                    ?>
                  </ul>
                  </div>


                  <div class="col-12">
                     <!-- Ratings -->
                     <div class="mb-4 mx-3 mx-xl-0 mt-3 mt-xl-0">
                    <div class="collapse show px-3" >
                    <h6 class="color-gray-900 mt-20 mb-10">{{tran('Ratings')}}</h6>
                        <label class="aiz-checkbox mb-3">
                            <input
                                type="radio"
                                name="rating"
                                value="5" @if ($rating==5) checked @endif
                                onchange="filter()"
                            >
                            <span class="aiz-square-check"></span>
                            <span class="rating rating-mr-1">{{ render_newStarRating(5) }}</span>
                        </label>
                        <br>
                        <label class="aiz-checkbox mb-3">
                            <input
                                type="radio"
                                name="rating"
                                value="4" @if ($rating==4) checked @endif
                                onchange="filter()"
                            >
                            <span class="aiz-square-check"></span>
                            <span class="rating rating-mr-1">{{ render_newStarRating(4) }}</span>
                            <span class="fs-14 fw-400 text-dark">{{ tran('And Up')}}</span>
                        </label>
                        <br>
                        <label class="aiz-checkbox mb-3">
                            <input
                                type="radio"
                                name="rating"
                                value="3" @if ($rating==3) checked @endif
                                onchange="filter()"
                            >
                            <span class="aiz-square-check"></span>
                            <span class="rating rating-mr-1">{{ render_newStarRating(3) }}</span>
                            <span class="fs-14 fw-400 text-dark">{{ tran('And Up')}}</span>
                        </label>
                        <br>
                        <label class="aiz-checkbox mb-3">
                            <input
                                type="radio"
                                name="rating"
                                value="2" @if ($rating==2) checked @endif
                                onchange="filter()"
                            >
                            <span class="aiz-square-check"></span>
                            <span class="rating rating-mr-1">{{ render_newStarRating(2) }}</span>
                            <span class="fs-14 fw-400 text-dark">{{ tran('And Up')}}</span>
                        </label>
                        <br>
                        <label class="aiz-checkbox mb-3">
                            <input
                                type="radio"
                                name="rating"
                                value="1" @if ($rating==1) checked @endif
                                onchange="filter()"
                            >
                            <span class="aiz-square-check"></span>
                            <span class="rating rating-mr-1">{{ render_newStarRating(1) }}</span>
                            <span class="fs-14 fw-400 text-dark">{{ tran('And Up')}}</span>
                        </label>
                        <br>
                    </div>
                </div>
                </div>
              </div>
             
             
              
        