<div class="topbar">
      <div class="container-topbar">
        <div class="menu-topbar-left d-none d-xl-block">
          <ul class="nav-small">
         
          </ul>
        </div>
        <div class="info-topbar text-center d-none d-xl-block"><span class="font-xs color-brand-3"></span><span class="font-sm-bold color-success"></span></div>
        <div class="menu-topbar-right"><span class="font-xs color-brand-3">{{tran('Need help? Call Us')}}:</span><span class="font-sm-bold color-success"> {{ get_setting('helpline_number') }}</span>
          <div class="dropdown dropdown-language">
            <?php
            $flags_arr['en']=asset('front/assets/imgs/template/en.svg');
            $flags_arr['ar']=asset('front/assets/imgs/template/ar.png');
            ?>
            <button class="btn dropdown-toggle" id="dropdownPage" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static"><span class="dropdown-right font-xs color-brand-3"><img src="{{$flags_arr[App::getLocale()]}}" alt="Ecom"> {{App::getLocale()}} </span></button>
            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownPage" data-bs-popper="static">
            <?php
            //  $route = Route::getCurrentRoute();
            //  $routeParameters = $route->parameters;
            // $shifted = array_shift($routeParameters);
            // $routeName = $route->getName();
            // $params = array_values($routeParameters);
             ?>
           
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li><a class="dropdown-item"  onclick="redirect(this.href)" rel="alternate"  hreflang="{{ $localeCode  }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"> {{ $properties['native']}}</a></li>
            @endforeach
            </ul>
          </div>

          <!-- Currency Switcher -->
          @if(get_setting('show_currency_switcher') == 'on')
          @php
        if(Session::has('currency_code')){
            $currency_code = Session::get('currency_code');
        }
        else{
            $currency_code = \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
    @endphp

          <div class="dropdown dropdown-language"  id="currency-change">
            <button class="btn dropdown-toggle" id="dropdownPage2" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
              <span class="dropdown-right font-xs color-brand-3"> {{ \App\Models\Currency::where('code', $currency_code)->first()->name }}</span></button>
            <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="dropdownPage2" data-bs-popper="static">
            @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)  
            
            <li class="drop-currency"><a class="dropdown-item @if($currency_code == $currency->code) active @endif"  onclick="changecurrency('{{ $currency->code }}')" href="javascript:void(0)" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a></li>

            @endforeach
             
            </ul>
          </div>
          @endif

        </div>
      </div>
    </div>
    <script>
      function redirect(url){
        window.location = url; 
      }
      </script>

         
