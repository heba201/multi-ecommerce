
          <div class="col-lg-3 order-last order-lg-first">
              <div class="sidebar-border mb-0">
                <div class="sidebar-head">
                  <h6 class="color-gray-900">{{tran('Product Categories')}}</h6>
                </div>
                <div class="sidebar-content">
                  <?php
                $categories = App\Models\Category::parent()->select('id', 'slug','name')->active()->orderBy('order_level', 'desc')->get();
                    ?>
                  <ul class="list-nav-arrow">
                  @isset($categories)
               @foreach($categories as $category)
                    <li><a href="{{route('category',$category -> slug )}}">{{$category -> name}}<span class="number">{{$category -> products()->count()}}</span></a></li>
                    @endforeach
                    @endisset
                   
                   
                  </ul>
                </div>
              </div>


              @include('front.includes.products_filter')
              

          