<footer class="footer">
<?php
              if(get_setting('footer_logo') !=""){
                $logo=asset('assets/images/website_settings/'.get_setting('footer_logo'));
              }else $logo=asset('front/assets/imgs/default.png');
              ?>
      <div class="footer-1">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 width-50 mb-30">
              <h4 class="mb-30 color-gray-1000">{{tran('Contact')}}</h4>
              <div class="font-md mb-20 color-gray-900"><strong class="font-md-bold">{{tran('Address')}}:</strong> {{ get_setting('contact_address',null,App::getLocale()) }}</div>
              <div class="font-md mb-20 color-gray-900"><strong class="font-md-bold">{{tran('Phone')}}:</strong> {{ get_setting('contact_phone') }}</div>
              <div class="font-md mb-20 color-gray-900"><strong class="font-md-bold">{{tran('E-mail')}}:</strong> {{ get_setting('contact_email') }}</div>
              @if ( get_setting('show_social_links') )
              <div class="mt-30">
              @if (!empty(get_setting('facebook_link')))
                <a class="icon-socials icon-facebook" href="{{ get_setting('facebook_link') }}" target="_blank"></a>
                @endif
                @if (!empty(get_setting('instagram_link')))
                <a class="icon-socials icon-instagram" href="{{ get_setting('instagram_link') }}" target="_blank"></a>
                @endif
                @if (!empty(get_setting('twitter_link')))
                <a class="icon-socials icon-twitter" href="{{ get_setting('twitter_link') }}"  target="_blank"></a>
                @endif 
                @if (!empty(get_setting('linkedin_link')))
                <a class="icon-socials icon-linkedin" href="{{ get_setting('linkedin_link') }}" target="_blank"></a>
                @endif
              </div>
              @endif
            </div>
            <?php
                $pages=App\Models\Page::where('type','!=','terms_conditions_page')->get();
              ?>
              @if($pages ->count() > 0)
            <div class="col-lg-4 width-50 mb-30">
              <h4 class="mb-30 color-gray-1000">{{tran('Useful Links')}}</h4>
              <ul class="menu-footer">
                @foreach($pages  as $page)
                <li><a href="{{route('custom-pages.show_custom_page',$page->slug)}}">{{$page->title}}</a></li>
                @endforeach
              </ul>
            </div>
            @endif

            <div class="col-lg-4 width-25">
              <h4 class="mb-30 color-gray-1000">App &amp; Payment</h4>
              <div>
              @if((get_setting('play_store_link') != null) || (get_setting('app_store_link') != null))
                <p class="font-md color-gray-900">{{tran('Download our Apps')}}&mldr;!</p>
                <div class="mt-20">
                  <a class="mr-10" href="{{ get_setting('app_store_link') }}">
                  <img src="{{asset('assets/imgs/template/appstore.png')}}" alt="Ecom">
                </a>
                  <a href="{{ get_setting('play_store_link') }}">
                    <img src="{{asset('assets/imgs/template/google-play.png')}}" alt="Ecom">
                  </a>
                </div>
                @endif
                @if ( get_setting('payment_method_images') !=  null ) <p class="font-md color-gray-900 mt-20 mb-10">{{tran('Secured Payment Gateways')}}</p><img src="{{asset('assets/images/website_settings/'.get_setting('payment_method_images'))}}" alt="Ecom">@endif
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="footer-2">
        <div class="footer-bottom-1">
          <div class="container">
            <div class="footer-2-top mb-20"><a href="{{route('home')}}"><img alt="Ecom" src="{{$logo}}" width="159" height="53"></a></div>
            <div class="footer-2-bottom">
              <div class="head-left-footer">
                <h6 class="color-gray-1000">{{tran('Categories')}}:</h6>
              </div>
              <div class="tags-footer">
              <?php
         $data = [];
         $data['categories'] = App\Models\Category::parent()->select('id','name','slug')->active()->orderBy('order_level', 'desc')->take(20)->get();
         $categories= $data['categories'];
        ?>
                  @isset($categories)
                  @foreach($categories as $category)
                <a href="{{route('category',$category -> slug )}}">{{$category -> name}}</a>
                @endforeach
                @endisset
              </div>
            </div>
           
          </div>
        </div>
        <div class="container">
          <div class="footer-bottom mt-20">
            <div class="row">
              <div class="col-lg-6 col-md-12 text-center text-lg-start"><span class="color-gray-900 font-sm">   {!! get_setting('frontend_copyright_text',null,App::getLocale()) !!}</span></div>
              <div class="col-lg-6 col-md-12 text-center text-lg-end">
                <ul class="menu-bottom">
                  <li><a class="font-sm color-gray-900" href="{{route('site.page','terms_conditions_page')}}">{{ tran('Terms & conditions') }}</a></li>  
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>