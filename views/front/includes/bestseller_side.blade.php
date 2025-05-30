@php
    $best_selers = Cache::remember('best_selers', 86400, function () {
        return \App\Models\Shop::where('verification_status', 1)->orderBy('num_of_sale', 'desc')->take(20)->get();
    });   
@endphp
              <div class="box-slider-item mb-30">
                <div class="head pb-15 border-brand-2">
                  <h5 class="color-gray-900">{{tran('Best seller')}}</h5>
                </div>
                <div class="content-slider">
                  <div class="box-swiper slide-shop">
                   
                  
                  <div class="swiper-container swiper-best-seller">
                  <div class="swiper-wrapper pt-5">
                        <div class="swiper-slide">
                        @foreach ($best_selers as $key => $seller)
                        @if ($seller->user != null)
                        
                        <div class="card-grid-style-2 card-grid-none-border border-bottom mb-10">
                            <div class="image-box" style="padding:10px"><a href="{{ route('shop.visit', $seller->slug) }}"><img src="{{$seller->logo !='' ? asset('assets/images/shops/'.$seller->logo) : asset('assets/images/default.png')}}" alt="Ecom"></a>
                            </div>
                            <div class="info-right"><a class="color-brand-3 font-xs-bold" href="{{ route('shop.visit', $seller->slug) }}">{{ $seller->name }}</a>
                              <div class="rating">{{ render_newStarRating($seller->rating) }}<span class="font-xs color-gray-500">({{ $seller->rating }})</span></div>
                              <div class="price-info"><a href="{{ route('shop.visit', $seller->slug) }}">{{tran('visit Store')}}</a>
                              </div>
                            </div>
                          </div> 
                        </div>
                        @endif
                        @endforeach
                      </div>
                    </div>


                    <div class="swiper-button-next swiper-button-next-style-2 swiper-button-next-bestseller"></div>
                    <div class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-bestseller"></div>
                  </div>
                </div>
              </div>