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
           <form action="{{route('products.filter')}}" method="GET">
            @csrf
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
                      <div class="col-lg-12">
                        <label class="lb-slider font-sm color-gray-500">{{tran('Price Range')}} :</label><span class="min-value-money font-sm color-gray-1000"></span>
                        <label class="lb-slider font-sm font-medium color-gray-1000"></label>-
                        <span class="max-value-money font-sm font-medium color-gray-1000"></span>
                      </div>
                      <div class="col-lg-12">
                        <input class="form-control min-value" type="hidden" name="min_value" value="">
                        <input class="form-control max-value" type="hidden" name="max_value" value="">
                      </div>
                    </div>
                  </div>
                 
                  <h6 class="color-gray-900 mt-20 mb-10">{{tran('Brands')}}</h6>
                  <ul class="list-checkbox">
                   <?php
                  $brands=App\Models\Brand::get();
                  if( $brands ->count() > 0){
                    $i=0;
                  foreach($brands as $brand){
                  ?>
                  <li>
                      <label class="cb-container">
                        <input name="brand[]" value="{{$brand->id}}" type="checkbox" <?php  echo $i==0 ? 'checked':''?>><span class="text-small">{{$brand->name}}</span><span class="checkmark"></span>
                      </label><span class="number-item">{{$brand->products->count()}}</span>
                    </li>
                    <?php
                     $i++;
                  }
                }
                    ?>
                  </ul>
                 
                  <?php
                   if (get_setting('color_filter_activation')){
                   
                  $colors_arr=[];
                   $product_colors=App\Models\Product::where('colors','!=','[]')->get();
                 if($product_colors ->count() > 0){
                foreach($product_colors as $product){
                  $colors=json_decode($product->colors);
                  for($j=0;$j<count($colors);$j++){
                    if(!in_array($colors[$j],$colors_arr)){
                      $colors_arr[]=$colors[$j];
                    }
                  }
                }
                  ?>
                  <h6 class="color-gray-900 mt-20 mb-10">{{tran('Color')}}</h6>
                  <ul class="list-color">
                    <?php
                  // input type="checkbox"
                    if(count($colors_arr) > 0){
                      for($l=0;$l<count($colors_arr);$l++){
                         $color=App\Models\Color::where('code',$colors_arr[$l])->first();
                       ?>
                 <li>   <input class="input-checkbox <?php echo $l==0 ?'active':'' ;?>"  type="checkbox" name="colorchk[]" value="{{$colors_arr[$l]}}" style="background-color:<?php echo $colors_arr[$l]; ?>"><span></span></li> 
              
                   <?php
                    }
                }

                ?>
                  </ul>
                  
           
                  <?php

              }
                  }
            ?>
             <button class="btn btn-secondary font-sm color-brand-3 font-medium mt-10" style="background-color:#425a8b !important;color:white !important"><i class="fa-solid fa-magnifying-glass"></i> {{tran("Search")}}</button>
                </div>
              </div>
              </form>
             
              
           