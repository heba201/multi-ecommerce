
<style>
  select{
  border:1px solid #392c70;
  padding: 10px 10px;
  border-radius:5px;
  background-color:white;
  color:#392c70;
  font-size:15px;
}
select option{
  padding: 10px 10px;
}
select:focus{
  outline:none;
}
</style>
<?php
								if(get_setting('header_logo') !=""){
									$img=asset('assets/images/website_settings/'.get_setting('header_logo'));
								}else $img=asset('front/assets/imgs/default.png');
								?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{route('admin.dashboard')}}"><img src="{{$img}}" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{route('admin.dashboard')}}"><img src="{{$img}}" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="fas fa-bars"></span>
        </button>
        <ul class="navbar-nav">
          <li class="nav-item nav-search d-none d-md-flex">
            <div class="nav-link">
              
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item dropdown">
          <select name="language" id="language" onchange="location = this.value;">
          @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
              <option value="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" <?php echo $localeCode == App::getLocale() ? 'selected':'';?>>
                          {{ $properties['native'] }}
              </option>
              @endforeach
            </select>
                </li>

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="fas fa-bell mx-0"></i>
              @if(Auth::user()->unreadNotifications->count() > 0)
              <span class="count notification-counter">{{Auth::user()->unreadNotifications->count()}}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <a class="dropdown-item">
                <p class="mb-0 font-weight-normal float-left">{{ tran('Notifications') }}
                </p>
              </a>
              @forelse(Auth::user()->unreadNotifications->take(5)->sortBy('created_at') as $notification)
              @if($notification->type == 'App\Notifications\OrderNotification')
              <div class="dropdown-divider"></div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-medium">
                    <a href="{{route('admin.orders.orderdetails', encrypt($notification->data['order_id']))}}" class="dropdown-item">{{tran('Order code: ')}} {{$notification->data['order_code']}} {{ tran('has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}</a></h6>
                  <p class="font-weight-light small-text dropdown-item">
                  {{ date("F j Y, g:i a", strtotime($notification->created_at)) }}
                  </p>
                </div>
              @endif
              @empty
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-medium"> {{ tran('No notification found') }}</h6>
                </div>
              </a>
              @endforelse
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item" href="{{ route('admin.all-notification') }}""> 
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-medium">{{tran('View All')}}</h6>
                </div> 
              </a>
            </div>
          </li>
        
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
            <?php
             if(Auth::user()->avatar_original !=""){
              $avatar=asset('assets/images/users/'.Auth::user()->avatar_original);
             }else $avatar=asset('assets/images/users/avatar.png');
             ?>
            <img src="{{$avatar}}" alt="profile"/>&nbsp; <?php echo Auth()->user()->name; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="{{route('edit.profile')}}">
                <i class="fas fa-user"></i>
                {{tran('Update profile')}}
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('admin.logout')}}">
                <i class="fas fa-power-off text-primary"></i>
                {{tran('Logout')}}
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="fas fa-bars"></span>
        </button>
      </div>
    </nav>


 


   