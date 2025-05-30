@extends('layouts.site')

@section('content')
<div class="section-box">
        <div class="breadcrumbs-div">
          <div class="container">
            <ul class="breadcrumb">
              <li><a class="font-xs color-gray-1000" href="{{route('home')}}">{{tran('Home')}}</a></li>
              <li><a class="font-xs color-gray-500" href="blog.html">{{tran('Vendor listing')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
<section class="section-box shop-template mt-0">
        <div class="container">
          <h2>{{tran('Vendors Listing')}}</h2>
          <div class="row align-items-center">
            <div class="col-lg-6 mb-30">
              <p class="font-md color-gray-500">{{tran('We have')}}<span class="font-md-bold color-brand-3"> {{App\Models\User::where('user_type','seller')->count()}} </span><span> {{tran('vendors now')}}</span></p>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-9 order-first order-lg-last">
             
              <div class="row mt-20">
             @if($shops->count() > 0)
             @foreach($shops as $shop)
              <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                  <div class="card-vendor">
                    <div class="card-top-vendor">
                    @php
                  if($shop->logo !=""){
                      $img=asset('assets/images/shops/'.$shop->logo);
                  }else{
                      
                      $img=asset('front/assets/imgs/default.png'); 
                  }
                  @endphp
                      <div class="card-top-vendor-left"><img src="{{$img}}" alt="Ecom">
                        <div class="rating">{{ render_newStarRating($shop->rating) }}<span class="font-xs color-gray-500"> ({{$shop->rating}})</span></div>
                      </div>
                      <?php
                      $user=App\Models\User::find($shop->user_id);
                      ?>
                      <div class="card-top-vendor-right"><a class="btn btn-gray" href="{{ route('shop.visit', $shop->slug) }}">{{ $user->published_products->count()}} Products</a>
                        <p class="font-xs color-gray-500 mt-10">{{tran('Member since')}} {{$shop->created_at->format('Y'); }}</p>
                      </div>
                    </div>
                    <div class="card-bottom-vendor">
                      <p class="font-sm color-gray-500 location mb-10">{{$shop->address}}</p>
                      <p class="font-sm color-gray-500 phone">{{$shop->phone}}&nbsp;</p>
                    </div>
                  </div>
                </div>
                @endforeach
             @endif
              </div>
              @if($shops->count() > 0)
              <nav>
                <ul class="pagination">
                {!! $shops->links() !!}
                </ul>
              </nav>
              @endif
            </div>
            @include('front.includes.products_catgeroies_side_vendor')
              <br>
              <div class="box-slider-item">
                <div class="head pb-15 border-brand-2">
                  <h5 class="color-gray-900">{{tran('Vendor by slug')}}</h5>
                </div>
                @if($shops->count() > 0)
                <div class="content-slider mb-50">
                @foreach($shops as $shop)
                <a class="btn btn-border mr-5" href="{{route('vendor.list',$shop->slug)}}">{{$shop->slug}}</a>
                @endforeach
                @endif
              </div>
          </div>
        </div>
      </section>
      @endsection