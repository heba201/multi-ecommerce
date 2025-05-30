<style>
  .badge-notify{
    position: absolute;
    left:85px;

    background-color: #FD9636;
    border-radius: 50%;
    position: absolute;
    width: 20px;
    height: 20px;
    color: #ffffff;
    text-align: center
  }
</style>
<header class="header sticky-bar">
      <div class="container">
        <div class="main-header">
        <?php
              if(get_setting('header_logo') !=""){
                $logo=asset('assets/images/website_settings/'.get_setting('header_logo'));
              }else $logo=asset('front/assets/imgs/default.png');
              
            $data = [];
         $data['categories'] = App\Models\Category::parent()->select('id', 'slug','name')->orderBy('order_level', 'desc')->active()->take(20)->with(['childrens' => function ($q) {
             $q->select('id', 'parent_id', 'slug','name');
             $q->with(['childrens' => function ($qq) {
                 $qq->select('id', 'parent_id', 'slug');
             }]);
         }])->get();
         $categories= $data['categories'];
         ?>
          <div class="header-left">
            <div class="header-logo"><a class="d-flex" href="{{route('home')}}"><img alt="Ecom" src="{{$logo}}" width="111" height="37"></a></div>
            <div class="header-search">
            
              <div class="box-header-search">
                <form class="form-search" method="GET" action="{{route('products.filter')}}">
                  <div class="box-category">
                    <select class="select-active select2-hidden-accessible" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    @isset($categories)
                    <option>{{trans('All Categories')}}</option>
                   @foreach($categories as $category)
                    <option value="{{route('category',$category -> slug )}}">{{$category -> name}}</option>
                    @endforeach
                     @endisset
                    </select>
                  </div>
                  <div class="box-keysearch">
                    <input class="form-control font-xs" type="text"  name="query" value="" placeholder="Search for items">
                  </div>
                </form>
              </div>
            </div>
            <div class="header-nav">
              <nav class="nav-main-menu d-none d-xl-block">
                <ul class="main-menu">
                  <li><a class="active" href="{{route('home')}}">{{tran('Home')}}</a>
                 
                  </li>
              
                
                  @if(App\Models\Page::where('type','about_us_page')->first() !=null)
                  <li><a href="{{route('site.page','about_us_page')}}">{{tran('About Us')}}</a>
                  
                  </li>
                  @endif
                 @if(App\Models\Page::where('type','contact_us_page')->first() !=null)
                  <li ><a href="{{route('site.page','contact_us_page')}}">{{tran('Contact Us')}}</a>
                    
                  </li>
                  @endif
                
                </ul>
              </nav>
              <div class="burger-icon burger-icon-white"><span class="burger-icon-top"></span><span class="burger-icon-mid"></span><span class="burger-icon-bottom"></span></div>
            </div>
            <div class="header-shop">
              <div class="d-inline-block box-dropdown-cart">
                <span class="font-lg icon-list icon-account"><span>{{tran('Account')}}</span>
              </span>
                <div class="dropdown-account">
                  <ul>
                  @auth()
                  @if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') 
                  <li><a href="{{route('admin.dashboard')}}">{{tran('Dashboard')}}</a></li>
                  @endif
                 
                  @if (auth()->user()->user_type == 'seller') 
                  <li><a href="{{route('dashboard')}}">{{tran('Dashboard')}}</a></li>
                  @endif
                  @endauth
                    <li><a href="{{route('all-notifications')}}">{{tran('Notifications')}} @auth() @if(Auth::user()->unreadNotifications->count() > 0)<strong class="badge badge-notify" style="{{Auth::user()->user_type=='customer' ? 'top:7px':'top:45px'}}">{{Auth::user()->unreadNotifications->count()}}</strong>@endif @endauth</a></li>
                    <li><a href="{{route('orders.track')}}">{{tran('Order Tracking')}}</a></li>
                    <li><a href="{{route('purchase_history.index')}}">{{tran('My Orders')}}</a></li>
                    <li><a href="{{route('wishlist.products.index')}}">{{tran('My Wishlist')}}</a></li>
                    <li><a href="{{route('customer-conversations.index')}}">{{tran('Conversations')}}</a></li>
                    <li><a href="{{route('profile')}}">{{tran('profile')}}</a></li>
                    @guest()
                    <li> <a  href="{{route('register')}}"
                               data-link-action="display-register-form">
                                Register
                            </a>
                           |
                            <a  href="{{route('login')}}" rel="nofollow" title="Log in to your customer account">{{tran('Sign in')}}</a>
                                </li>
                        @endguest
             
                @auth()
               
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{tran('Sign out')}}</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                   @endauth
                  </ul>
                </div>
              </div><a class="font-lg icon-list icon-wishlist" href="{{route('wishlist.products.index')}}"><span>{{tran('Wishlist')}}</span><span class="number-item font-xs" id="wishlist_count">{{auth()->user() !=null ? App\Models\Wishlist::where('user_id', Auth::user()->id)->count() : 0}}</span></a>
              <div class="d-inline-block box-dropdown-cart"><span class="font-lg icon-list icon-cart"><span>{{tran('Cart')}}</span><span class="number-item font-xs" id="cart_count">{{auth()->user() !=null ? App\Models\Cart::where('user_id', Auth::user()->id)->count() : 0}}</span></span>
               @include('front.includes.cartnav')


              </div><a class="font-lg icon-list icon-compare" href="{{route('compare')}}"><span>{{tran('Compare')}}</span></a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
      <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
          <div class="mobile-logo"><a class="d-flex" href="{{route('home')}}"><img alt="Ecom" src="assets/imgs/template/logo.svg"></a></div>
          <div class="perfect-scroll">
            <div class="mobile-menu-wrap mobile-header-border">
              <nav class="mt-15">
                <ul class="mobile-menu font-heading">
                  <li><a class="active" href="{{route('home')}}">{{tran('Home')}}</a>    
                  </li>
                   
               
                  @if(App\Models\Page::where('type','about_us_page')->first() !=null)
                  <li><a href="{{route('site.page','about_us_page')}}">{{tran('About Us')}}</a>
                  
                  </li>
                  @endif
                 @if(App\Models\Page::where('type','contact_us_page')->first() !=null)
                  <li ><a href="{{route('site.page','contact_us_page')}}">{{tran('Contact Us')}}</a>
                    
                  </li>
                  @endif
                </ul>
              </nav>
            </div>
            @auth()
            <div class="mobile-account">
            @php
              if(auth()->user()->avatar_original !=""){
                $img=asset('assets/images/users/'.auth()->user()->avatar_original);
              }else $img=asset('assets/images/users/avatar.png');
              @endphp
              <div class="mobile-header-top">
                <div class="user-account"><a href="{{route('profile')}}"><img src="{{$img}}" alt="Ecom"></a>
                  <div class="content">
                  <h6 class="user-name">{{tran('Hello')}}<span class="text-brand"> {{auth()->user()->name}} !</span></h6> 
                    <p class="font-xs text-muted">You have 3 new messages</p>
                  </div>
                </div>
              </div>
              @endauth
              <ul class="mobile-menu">
              @auth()
              @if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') 
                  <li><a href="{{route('admin.dashboard')}}">{{tran('Dashboard')}}</a></li>
                  @endif

                  @if (auth()->user()->user_type == 'seller') 
                  <li><a href="{{route('dashboard')}}">{{tran('Dashboard')}}</a></li>
                  @endif
                  @endauth
              <li><a href="{{route('all-notifications')}}">{{tran('Notifications')}} @auth()@if(Auth::user()->unreadNotifications->count() > 0)<span class="badge badge-notify" style="{{Auth::user()->user_type=='customer' ? 'top:7px':'top:45px'}}">{{Auth::user()->unreadNotifications->count()}}</span>@endif @endauth</a></li>
                    <li><a href="{{route('orders.track')}}">{{tran('Order Tracking')}}</a></li>
                    <li><a href="{{route('purchase_history.index')}}">{{tran('My Orders')}}</a></li>
                    <li><a href="{{route('wishlist.products.index')}}">{{tran('My Wishlist')}}</a></li>
                    <li><a href="{{route('customer-conversations.index')}}">{{tran('Conversations')}}</a></li>
                    <li><a href="{{route('profile')}}">{{tran('profile')}}</a></li>
             
             
             
             
                @guest()
                            <a class="register" href="{{route('register')}}"
                               data-link-action="display-register-form">
                                Register
                            </a>
                            <span class="or-text">or</span>
                            <a class="login" href="{{route('login')}}" rel="nofollow" title="Log in to your customer account">Sign
                                in</a>
                        @endguest
             
                @auth()
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            @endauth
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>