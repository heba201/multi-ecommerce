<div class="sidebar-left"><a class="btn btn-open" href="#"></a>

<?php
 
         $data = [];
         $data['categories'] = App\Models\Category::parent()->select('id', 'slug','name','banner')->active()->orderBy('order_level', 'desc')->take(20)->with(['childrens' => function ($q) {
             $q->select('id', 'parent_id', 'slug','name');
             $q->with(['childrens' => function ($qq) {
                 $qq->select('id', 'parent_id', 'slug');
             }]);
         }])->get();
         $categories= $data['categories'];
        ?>

      <ul class="menu-icons hidden">
      @isset($categories)
     @foreach($categories as $category)
     @php
     $catimg=asset('assets/images/default.png');
    
    if(count(explode("categories/",$category->banner)) > 1){
    $catimg=$category->banner;
    }
    @endphp
        <li><a href="{{route('category',$category -> slug )}}"><img class="rounded-circle" width="30" height="30" src="{{$catimg}}" alt="Ecom"></a></li>
    @endforeach
   @endisset
        
      </ul>
      <ul class="menu-texts menu-close">
      @isset($categories)
      @foreach ($categories as $key => $category)
      @php
     $catimg=asset('assets/images/default.png');
    
    if(count(explode("categories/",$category->banner)) > 1){
    $catimg=$category->banner;
    }
    @endphp
        <li class="has-children"><a href="{{route('category',$category -> slug )}}"><span class="img-link"><img width="30" height="30" class="rounded-circle" src="{{$catimg}}" alt="Ecom"></span><span class="text-link">{{$category -> name}}</span></a>
          <ul class="sub-menu">
          @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
          @php
              $first_level_categories = \App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id);
          @endphp
        
            <li><a href="{{ route('category', \App\Models\Category::find($first_level_id)->slug) }}">{{ \App\Models\Category::find($first_level_id)->name}}</a>
             @if(count($first_level_categories) > 0)
            <ul class="sub-menu">
            @foreach ($first_level_categories as $key => $second_level_id)
            <li><a href="{{ route('category', \App\Models\Category::find($second_level_id)->slug) }}" >{{ \App\Models\Category::find($second_level_id)->name}}</a>
                  </li>
                  @endforeach   
              </ul>
             @endif
             </li>
            @endforeach
          </ul>
        </li>
        @endforeach
   @endisset
      </ul>
    </div>