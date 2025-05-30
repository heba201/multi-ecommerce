<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
      <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
        <?php
              if(get_setting('header_logo') !=""){
                $logo=asset('assets/images/website_settings/'.get_setting('header_logo'));
              }else $logo=asset('front/assets/imgs/default.png');
              ?>
          <div class="mobile-logo"><a class="d-flex" href="{{route('home')}}"><img alt="Ecom" src="{{$logo}}" width="111" height="37"></a></div>
          <div class="perfect-scroll">
            <div class="mobile-menu-wrap">
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
              <div class="mobile-header-top">
              @php
              if(auth()->user()->avatar_original !=""){
                $img=asset('assets/images/users/'.auth()->user()->avatar_original);
              }else $img=asset('assets/images/users/avatar.png');
              @endphp
              <div class="user-account"><a href="{{route('profile')}}"><img src="{{$img}}" alt="Ecom"></a>
                  <div class="content">
                    <h6 class="user-name">{{tran('Hello')}}<span class="text-brand"> {{auth()->user()->name}} !</span></h6> 
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
                  @auth()
              <li><a href="{{route('all-notifications')}}">{{tran('Notifications')}}</a></li>
                    <li><a href="{{route('orders.track')}}">{{tran('Order Tracking')}}</a></li>
                    <li><a href="{{route('purchase_history.index')}}">{{tran('My Orders')}}</a></li>
                    <li><a href="{{route('wishlist.products.index')}}">{{tran('My Wishlist')}}</a></li>
                    <li><a href="{{route('customer-conversations.index')}}">{{tran('Conversations')}}</a></li>
                    <li><a href="{{route('profile')}}">{{tran('profile')}}</a></li>
                    @endauth
                  
             
                @auth()
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{tran('Sign out')}}</a></li>
              
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