
          <div class="col-lg-3 order-last order-lg-first">
              <div class="sidebar-border mb-0">
                <div class="sidebar-head">
                  <h6 class="color-gray-900">{{tran('Product Categories')}}</h6>
                </div>
                <div class="sidebar-content">
               
                  <?php
                  $category_ids = \App\Models\Product::where('user_id', $shop->user->id)->where('published', 1)->where('approved', 1)->pluck('category_id')->toArray();
                 ?>
                  <ul class="list-nav-arrow">
                  
               @foreach(\App\Models\Category::whereIn('id', $category_ids)->get() as $category)
               <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="checkbox"
                                                        name="selected_categories[]"
                                                        value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ $category->name }}</span>
                                                </label>
                                                <br>
                    @endforeach
                  
                   
                   
                  </ul>
                </div>
              </div>


              @include('front.includes.shop_filter_price_brand',$shop->user)
              
              
           


          