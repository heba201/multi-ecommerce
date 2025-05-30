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
              $avatar=asset('assets/images/users/'.Auth::user()->avatar_original);
             }else $avatar=asset('assets/images/users/avatar.png');
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
                  if(Auth()->user()->user_type=="admin"){
                    $user="Super Admin";
                  }if(Auth()->user()->user_type=="staff"){
                    $user="Staff";
                  }
                  ?>
                 {{$user}}
                </p>
              </div>
            </div>
          </li>
          @can('admin_dashboard')
          <li class="nav-item">
            <a class="nav-link" href="{{route('admin.dashboard')}}">
              <i class="fa fa-home menu-icon"></i>
              <span class="menu-title">{{tran('Dashboard')}}</span>
            </a>
          </li>
          @endcan
          
          @canany(['view_product_categories', 'add_product_category'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sidebar-layouts" aria-expanded="false" aria-controls="sidebar-layouts">
              <i class="fa-solid fa-list menu-icon"></i>
              <span class="menu-title">{{tran('Categories')}}</span> &nbsp;<span class="badge badge-pill badge-success">{{ App\Models\Category::count()}}</span>&nbsp;
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sidebar-layouts">
              <ul class="nav flex-column sub-menu">
              @can('view_product_categories')
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.categories')}}">{{tran('Show All')}}</a></li>
                @endcan
                @can('add_product_category')
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.categories.create')}}">{{tran('Add New Category')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany
          @canany(['view_all_brands', 'add_brand'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced">
              <i class="fas fa-clipboard-list menu-icon"></i>
              <span class="menu-title">{{tran('Brands')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
              @can('view_all_brands')
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.brands')}}">{{tran('Show All')}}</a></li>
                @endcan
                @can('add_brand')
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.brands.create')}}">{{tran('Add New Brand')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany
          @canany(['add_new_product', 'show_all_products','show_in_house_products','show_seller_products','show_digital_products','product_bulk_import','product_bulk_export','view_product_categories', 'view_all_brands','view_product_attributes','view_colors','view_product_reviews'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#editors" aria-expanded="false" aria-controls="editors">
              <i class="fa-solid fa-bag-shopping menu-icon"></i>
              <span class="menu-title">{{tran('Products')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="editors">
              <ul class="nav flex-column sub-menu">
              @can('show_all_products')
                <li class="nav-item"><a class="nav-link" href="{{route('products.all')}}">{{tran('Show All')}}</a></li>
                @endcan
                @can('show_in_house_products')
                <li class="nav-item"><a class="nav-link" href="{{route('admin.products')}}">{{tran('In House Products')}}</a></li>
                @endcan
                @if(get_setting('vendor_system_activation') == 1)
                @can('show_seller_products')
                <li class="nav-item"><a class="nav-link" href="{{route('products.seller')}}">{{tran('Seller Products')}}</a></li>
                @endif
                @endcan
                @can('add_new_product')
                <li class="nav-item"><a class="nav-link" href="{{route('admin.products.general.create')}}">{{tran('Add New Product')}}</a></li>
                @endcan
              
                @can('view_product_attributes')
                <li class="nav-item"><a class="nav-link" href="{{route('attributes.index')}}">{{tran('Attributes')}}</a></li>
                @endcan

                @can('view_colors')
                <li class="nav-item"><a class="nav-link" href="{{route('admin.colors')}}">{{tran('Colors')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany
          @canany(['view_all_flash_deals','add_flash_deal'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#flash" aria-expanded="false" aria-controls="flash">
              <i class="fa-solid fa-bolt menu-icon"></i>
              <span class="menu-title">{{tran('Flash Deals')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="flash">
              <ul class="nav flex-column sub-menu">
              @can('view_all_flash_deals')
                <li class="nav-item"><a class="nav-link" href="{{ route('flash_deals.index') }}">{{tran('Show All')}}</a></li>
                @endcan
                @can('add_flash_deal')
                <li class="nav-item"><a class="nav-link" href="{{ route('flash_deals.create') }}">{{tran('Add New  Flash Deal')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany

          @canany(['view_all_orders', 'view_inhouse_orders','view_seller_orders','view_pickup_point_orders'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#e-commerce" aria-expanded="false" aria-controls="e-commerce">
              <i class="fas fa-shopping-cart menu-icon"></i>
              <span class="menu-title">{{tran('E-commerce')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="e-commerce">
              <ul class="nav flex-column sub-menu">
              @can('view_all_orders')
              <li class="nav-item"> <a class="nav-link" href="{{ route('all_orders.index') }}"> {{tran('All Orders')}} </a></li>
              @endcan
              @can('view_inhouse_orders')
              <li class="nav-item"> <a class="nav-link" href="{{ route('inhouse_orders.index') }}"> {{tran('Inhouse Orders')}} </a></li>
              @endcan
              @can('view_seller_orders')
              <li class="nav-item"> <a class="nav-link" href="{{route('seller_orders.index')}}"> {{tran('Seller Orders')}} </a></li>
              @endcan
              @can('view_pickup_point_orders')
              <li class="nav-item"> <a class="nav-link" href="{{route('pick_up_point.index')}}"> {{tran('Pick-up Point Order')}} </a></li>
              @endcan 
              </ul>
            </div>
          </li>
          @endcanany

            @can('view_all_customers')
                  <li class="nav-item">
            <a class="nav-link" href="{{ route('customers.index') }}">
            <i class="fa-solid fa-users menu-icon"></i>
              <span class="menu-title">{{ tran('Customers') }}</span>
            </a>
          </li>
          @endcan
              
          @canany(['view_all_seller','seller_payment_history','view_seller_payout_requests','seller_commission_configuration','view_all_seller_packages','seller_verification_form_configuration'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sellers" aria-expanded="false" aria-controls="sellers">
            <i class="fa-solid fa-store menu-icon"></i>
              <span class="menu-title">{{tran('Sellers')}}</span>
              @php
                $sellers = \App\Models\Shop::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                @endphp
                @if($sellers > 0) &nbsp;<span class="badge badge-pill badge-success">{{$sellers}}</span>&nbsp;@endif
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sellers">
              <ul class="nav flex-column sub-menu">
              @can('view_all_seller')
              <li class="nav-item"> <a class="nav-link" href="{{route('sellers.index')}}"> {{tran('Sellers')}} </a></li>
              @endcan
              @can('view_seller_payout_requests')
              <li class="nav-item"> <a class="nav-link" href="{{route('withdraw_requests_all')}}"> {{tran('Payout Requests')}} </a></li>
              @endcan
              @can('seller_payment_history')
              <li class="nav-item"> <a class="nav-link" href="{{route('sellers.payment_histories')}}"> {{tran('Payouts')}} </a></li>
              @endcan
              @can('seller_commission_configuration')
              <li class="nav-item"> <a class="nav-link" href="{{route('business_settings.vendor_commission')}}">{{tran('Seller Comission')}}</a></li>
              @endcan
              @can('seller_verification_form_configuration')
              <li class="nav-item"> <a class="nav-link" href="{{route('seller_verification_form.index')}}">{{tran('Seller Verification Form')}}</a></li>
              @endcan
            </ul>
            </div>
          </li>
          @endcanany

          @canany(['in_house_product_sale_report','seller_products_sale_report','products_stock_report','product_wishlist_report','user_search_report','commission_history_report','wallet_transaction_report'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
            <i class="far fa-file-alt menu-icon"></i>
              <span class="menu-title">{{tran('Reports')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="reports">
              <ul class="nav flex-column sub-menu">
              @can('in_house_product_sale_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('in_house_sale_report.index') }}"> {{ tran('In House Product Sale') }} </a></li>
              @endcan
              @can('seller_products_sale_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('seller_sale_report.index') }}"> {{ tran('Seller Products Sale') }} </a></li>
              @endcan
              @can('products_stock_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('stock_report.index') }}"> {{ tran('Products Stock') }} </a></li>
              @endcan
              @can('product_wishlist_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('wish_report.index') }}">{{ tran('Products wishlist') }}</a></li>
              @endcan
              @can('user_search_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('user_search_report.index') }}">{{ tran('User Searches') }}</a></li>
              @endcan
              @can('commission_history_report')
              <li class="nav-item"> <a class="nav-link" href="{{ route('commission-log.index') }}">{{ tran('Commission History') }}</a></li>
              @endcan
            </ul>
            </div>
          </li>
          @endcanany

         

          @canany(['view_all_product_conversations','view_all_product_queries'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#support" aria-expanded="false" aria-controls="support">
              <i class="fa-solid fa-headset menu-icon"></i>
              <span class="menu-title">{{tran('Support')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="support">
              <ul class="nav flex-column sub-menu">
              @can('view_all_product_conversations')
              @php
                $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
            @endphp
                <li class="nav-item"> <a class="nav-link" href="{{ route('conversations.admin_index') }}">{{tran('Product Conversations')}} @if (count($conversation) > 0)&nbsp;<span class="badge badge-pill badge-success">{{ count($conversation) }}</span>&nbsp;@endif
                </a></li>
                @endcan
                @if (get_setting('product_query_activation') == 1)
                 @can('view_all_product_queries')
                <li class="nav-item"> <a class="nav-link" href="{{ route('product_query.index') }}">{{ tran('Product Queries')}}</a></li>
                @endcan
                @endif
              </ul>
            </div>
          </li>
          @endcanany


          @canany(['header_setup','footer_setup','view_all_website_pages','website_appearance'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#website_setup" aria-expanded="false" aria-controls="website_setup">
              <i class="fa fa-stop-circle menu-icon"></i>
              <span class="menu-title">{{tran('Website Setup')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="website_setup">
              <ul class="nav flex-column sub-menu">
              @can('header_setup')
                <li class="nav-item"> <a class="nav-link" href="{{ route('website.header') }}">{{tran('Header')}}</a></li>
                @endcan
                @can('footer_setup')
                <li class="nav-item"> <a class="nav-link" href="{{ route('website.footer', ['lang'=>  App::getLocale()] ) }}">{{ tran('Footer')}}</a></li>
                @endcan
                @can('website_appearance')
                <li class="nav-item"> <a class="nav-link" href="{{ route('website.appearance') }}">{{ tran('Appearance')}}</a></li>
                @endcan

                @can('view_all_website_pages')
                <li class="nav-item"> <a class="nav-link" href="{{ route('website.pages')  }}">{{ tran('Pages')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany
            <!-- Setup & Configurations -->
            @canany(['currency_setup','vat_&_tax_setup','features_activation','pickup_point_setup','smtp_settings','payment_methods_configurations','order_configuration'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#setup" aria-expanded="false" aria-controls="setup">
              <i class="fa-solid fa-gear  menu-icon"></i>
              <span class="menu-title">{{ tran('Setup & Configurations')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="setup">
            
              <ul class="nav flex-column sub-menu">
              @can('currency_setup')
                <li class="nav-item"> <a class="nav-link" href="{{route('currency.index')}}">{{ tran('Currency')}}</a></li>
                @endcan
                @can('vat_&_tax_setup')
                <li class="nav-item"> <a class="nav-link" href="{{route('tax.index')}}">{{ tran('Vat & Tax')}}</a></li>
                @endcan
                @can('pickup_point_setup')
                <li class="nav-item"> <a class="nav-link" href="{{route('pick_up_points.index')}}">{{ tran('Pickup Point')}}</a></li>
                @endcan
                @can('order_configuration')
                <li class="nav-item"> <a class="nav-link" href="{{route('order_configuration.index')}}">{{ tran('Order Configuration')}}</a></li>
                @endcan
                @can('smtp_settings')
                <li class="nav-item"> <a class="nav-link" href="{{ route('smtp_settings.index') }}">{{ tran('SMTP Settings')}}</a></li>
                @endcan

                @can('social_media_logins')
                <li class="nav-item"> <a class="nav-link" href="{{  route('social_login.index') }}">{{ tran('Social media Logins')}}</a></li>
                @endcan

                @can('features_activation')
                  <li  class="nav-item"><a  class="nav-link" href="{{route('activation.index')}}">{{tran('Features activation')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany

          @canany(['view_all_coupons','add_coupon'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="fas fa-window-restore menu-icon"></i>
              <span class="menu-title">{{ tran('Coupons')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
              @if (get_setting('coupon_system') == 1 && auth()->user()->can('view_all_coupons') )
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.coupons')}}">   {{ tran('View All')}}  </a></li>
                @endif
                @if (get_setting('coupon_system') == 1 && auth()->user()->can('add_coupon') )
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.coupons.create')}}">{{ tran('Add')}} </a></li>
                @endif
              </ul>
            </div>
          </li>
          @endcanany
        
          @canany(['shipping_configuration','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#apps" aria-expanded="false" aria-controls="apps">
              <i class="fa-solid fa-truck-fast menu-icon"></i>
              <span class="menu-title">{{tran('Shipping')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="apps">
              <ul class="nav flex-column sub-menu">
              @can('shipping_configuration')
              <li class="nav-item"><a class="nav-link" href="{{route('shipping_configuration.index')}}"> {{ tran('Shipping Configurations')}}</a></li>
              @endcan
              @can('shipping_country_setting')
              <li class="nav-item"><a class="nav-link" href="{{route('countries.index')}}">{{ tran('Shipping Countries')}}</a></li>
              @endcan
              @can('manage_shipping_states')
              <li class="nav-item"><a class="nav-link" href="{{route('states.index')}}">{{ tran('Shipping States')}}</a></li>
              @endcan
              @can('manage_shipping_cities')
              <li class="nav-item"><a class="nav-link" href="{{route('cities.index')}}">{{ tran('Shipping Cities')}}</a></li>
              @endcan
              @can('manage_zones')
              <li class="nav-item"><a class="nav-link" href="{{route('zones.index')}}">{{ tran('Shipping Zones')}}</a></li>
              @endcan
              @can('manage_carriers')
              <li class="nav-item"><a class="nav-link" href="{{route('carriers.index')}}">{{ tran('Shipping Carriers')}}</a></li>
              @endcan
            </ul>
            </div>
          </li>
          @endcanany
         
          @canany(['view_all_staffs','view_staff_roles'])
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#staff" aria-expanded="false" aria-controls="staff">
              <i class="fa-solid fa-user menu-icon"></i>
              <span class="menu-title">{{ tran('Staff')}}</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="staff">
              <ul class="nav flex-column sub-menu">
              @can('view_all_staffs')
                <li class="nav-item"> <a class="nav-link" href="{{ route('staffs.index') }}"> {{tran('All staff')}}  </a></li>
                @endcan
                @can('view_staff_roles')
                <li class="nav-item"> <a class="nav-link" href="{{route('roles.index')}}">{{tran('Staff permissions')}}</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endcanany
          @can('view_all_subscribers')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('subscribers.index') }}">
            <i class="fa-solid fa-users menu-icon"></i>
              <span class="menu-title">{{ tran('Subscribers') }}</span>
            </a>
          </li>
          @endcan
        </ul>
      </nav>