<style>
.badge{
  padding: 0.1rem 0.62rem !important;
}
  </style>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image">
                <?php
                if(Auth::user()->avatar_original !=""){
                  $avatar= asset('assets/images/users/'.Auth::user()->avatar_original);
                }else $avatar= asset('assets/images/users/avatar.png');
               
                ?>
                <img src="{{$avatar}}" alt="image"/>
              </div>
              <div class="profile-name">
                <p class="name">
                  {{Auth()->user()->name}}
                </p>
                <p class="designation">
                <?php
                  $user="";
                  if(Auth()->user()->user_type=="Seller"){
                    $user="Seller";
                  }
                  ?>
                {{$user}}
                </p>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
              <i class="fa fa-home menu-icon"></i>
              <span class="menu-title">{{tran('Dashboard')}}</span>
            </a>
          </li>
         
         
          
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#editors" aria-expanded="false" aria-controls="editors">
            <i class="fa-solid fa-bag-shopping menu-icon"></i>
              <span class="menu-title">{{tran('Products')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="editors">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="{{ route('seller.products') }}">{{ tran('Show All')}}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('seller.products.create')}}">{{ tran('Add New Product')}}</a></li>

                
                <li class="nav-item"><a class="nav-link" href="{{ route('reviews') }}">{{ tran('Product Reviews') }}</a></li>

              
              </ul>
            </div>
          </li>


          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#e-commerce" aria-expanded="false" aria-controls="e-commerce">
              <i class="fas fa-shopping-cart menu-icon"></i>
              <span class="menu-title">{{tran('E-commerce')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="e-commerce">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{ route('orders.index') }}"> {{tran('Orders')}} </a></li>   
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
            <i class="fa fa-stop-circle menu-icon"></i>
              <span class="menu-title">{{tran('Shop Setting')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('shop.index') }}">{{tran('Shop Setting')}}</a></li>
              </ul>
              </div>
          </li>
   
          @if (get_setting('coupon_system') == 1)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="fas fa-window-restore menu-icon"></i>
              <span class="menu-title"> {{tran('Coupons')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('seller.coupon.index') }}">   {{tran('View All')}}  </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('seller.coupon.create')}}">  {{tran('Add')}} </a></li>
              </ul>
            </div>
          </li>
          @endif
          
  
          <li class="nav-item">
            <a class="nav-link" href="{{route('payments.index')}}">
              <i class="fa-solid fa-clock-rotate-left menu-icon"></i>
              <span class="menu-title">{{ tran('Payment History') }}</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{route('money_withdraw_requests.index')}}">
              <i class="fa-solid fa-money-bill-transfer menu-icon"></i>
              <span class="menu-title">{{ tran('Money Withdraw') }}</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('commission-history.index') }}">
              <i class="fa-solid fa-money-check menu-icon"></i>
              <span class="menu-title">{{ tran('Commission History') }}</span>
            </a>
          </li>
          @if (get_setting('conversation_system') == 1)
                    @php
                        $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)
                        ->orwhere('receiver_id',Auth::user()->id)->where('receiver_viewed',0)
                            ->get();
                    @endphp
          <li class="nav-item">
            <a class="nav-link" href="{{ route('conversations.index') }}">
              <i class="fa-solid fa-message menu-icon"></i>
              <span class="menu-title">{{ tran('Conversations') }}</span>
              @if (count($conversation) > 0)
              <span class="badge badge-success">({{ count($conversation) }})</span>
              @endif
            </a>
          </li>
          @endif


          @if (get_setting('product_query_activation') == 1)
                  <li class="nav-item">
                        <a href="{{ route('seller.product_query.index') }}"
                        class="nav-link">
                            <i class="far fa-file-alt menu-icon"></i>
                            <span class="menu-title">{{ tran('Product Queries') }}</span>

                        </a>
                    </li>
                @endif

        </ul>
      </nav>