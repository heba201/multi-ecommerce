@extends('layouts.site')
@section('content')
<style>
 .menu-nav {
       
       display: flex;
       justify-content: space-between;
     }
     
     .menu-item {
      color: #444;
       padding: 3px;
     }
     
     .three-dots:after {
       cursor: pointer;
       color: #444;
       content: '\2807';
       font-size: 20px;
       padding: 0 5px;
     }
     
     a {
       text-decoration: none;
       color: #444;
     }
     a hover{
      color: #4abbf2;
     }
     a div {
       padding: 3px;
     }
     
     .dropdown {
       position: absolute;
       right: 10px;
       background-color: #f2f3f8;
       padding:5px;
       outline: none;
       opacity: 0;
       z-index: -1;
       max-height: 0;
       tranition: opacity 0.1s, z-index 0.1s, max-height: 5s;
       border-radius:5px;
     }
     
     .dropdown-container:focus {
       outline: none;
     }
     
     .dropdown-container:focus .dropdown {
       opacity: 1;
       z-index: 100;
       max-height: 100vh;
       tranition: opacity 0.2s, z-index 0.2s, max-height: 0.2s;
     }

</style>
<section class="section-box shop-template mt-30">
        <div class="container box-account-template">
          <h3>Hello {{auth()->user()->name}}</h3>
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">
              <li><a class="active" href="#tab-setting" data-bs-toggle="tab" role="tab" aria-controls="tab-setting" aria-selected="true">{{tran('Profile')}}</a></li>
            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
              
              <div class="tab-pane fade active show" id="tab-setting" role="tabpanel" aria-labelledby="tab-setting">
                <div class="row">
                  <div class="col-lg-6 mb-20">
                  <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                      <div class="row">
                        <div class="col-lg-12 mb-20">
                          <h5 class="font-md-bold color-brand-3 text-sm-start text-center">{{tran('Contact information')}}</h5>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" name="name" type="text" placeholder="Fullname *" value="{{Auth::user()->name}}" required>
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>
                    
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm"  name="phone" type="text" placeholder="Phone Number *" value="{{Auth::user()->phone}}" required>
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" name="email" type="text" placeholder="Email *" value="{{Auth::user()->email}}" required>
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="password" name="new_password" placeholder="{{tran('Password')}} *">
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="password" name="confirm_password"  placeholder="{{tran('Confirm Password')}} *">
                            @error('confirm_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>
                       
                        <div class="col-lg-12">
                          <div class="form-group">
                          @error('photo')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            <input class="form-control font-sm" name="photo" type="file">
                            @if(Auth::user()->avatar_original !="")<img style="margin-top:10px" src="{{ asset('assets/images/users/'.Auth::user()->avatar_original)}}" width="100" height="100">@endif
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mt-20">
                            <button class="btn btn-buy w-auto h54 font-md-bold" >{{tran('Save change')}}</button>
                          </div>
                        </div>
                      </div>
                      </form>
                  </div>
                 
                  <div class="col-lg-6 mb-20">
                    <div class="mt-40">
                     
                       <!-- Address -->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ tran('Address')}}</h5>
        </div>
        <div class="card-body">
            @foreach (Auth::user()->addresses as $key => $address)
         

            <div class="">
                <div class="border p-4 mb-4 position-relative">
                <div class="menu-nav">
        <div class="menu-item"></div>
        <div class="dropdown-container" tabindex="-1">
          <div class="three-dots"></div>
          <div class="dropdown">
            <a href="#" onclick="edit_address({{$address->id}})"><div>   {{ tran('Edit') }}</div></a>
            @if (!$address->set_default)
           <a href="{{ route('user.addresses.set_default', $address->id) }}"><div>{{ tran('Make This Default') }}</div></a>
            @endif
            <a  href="{{ route('user.addresses.destroy', $address->id) }}"><div>{{ tran('Delete') }}</div></a>
          </div>
        </div>
      </div>
                
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ tran('Address') }}:</span>
                            <span class="col-md-8 text-dark">{{ $address->address }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ tran('Postal Code') }}:</span>
                            <span class="col-md-10 text-dark">{{ $address->postal_code }}</span>
                        </div>



                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ tran('City') }}:</span>
                            <span class="col-md-10 text-dark">{{ optional($address->city)->name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ tran('State') }}:</span>
                            <span class="col-md-10 text-dark">{{ optional($address->state)->name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ tran('Country') }}:</span>
                            <span class="col-md-10 text-dark">{{ optional($address->country)->name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary text-secondary">{{ tran('Phone') }}:</span>
                            <span class="col-md-10 text-dark">{{ $address->phone }}</span>
                        </div>
                        @if ($address->set_default)
                            <div class="absolute-md-top-right pt-2 pt-md-4 pr-md-5">
                                <span class="badge badge-inline badge-warning text-white p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ tran('Default') }}</span>
                            </div>
                        @endif
                 
                    </div>
                </div>
            @endforeach
            <!-- Add New Address -->
            <div class="" onclick="add_new_address()">
                <div class="border p-3 mb-3 c-pointer text-center bg-light has-tranition hov-bg-soft-light">
                    <i class="la la-plus la-2x"></i>
                    <div class="alpha-7 fs-14 fw-700">{{ tran('Add New Address') }}</div>
                </div>
            </div>
        </div>
    </div>
                   
                   
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
   
    @endsection
@push('js')

@endpush
    @section('modal')
    <!-- Address Modal -->
    @include('modals.address_modal')
@endsection