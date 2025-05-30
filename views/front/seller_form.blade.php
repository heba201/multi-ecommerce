@extends('layouts.site')
@section('content')
<style>
    .invalid-feedback{
        display:block;
    }
    </style>
<section class="section-box shop-template mt-60">
<form method="POST" action="{{ route('shops.store') }}">
                                @csrf
        <div class="container">
          <div class="row mb-100">
            <div class="col-lg-1"></div>
            <div class="col-lg-5">
              <h3>{{tran('Create your shop')}}</h3>
             
              <div class="form-register mt-30 mb-30">
                <div class="form-group">
                  <label class="mb-5 font-sm color-gray-700">{{tran('Username')}} *</label>
                  <input type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  tran('Name') }}" name="name" required>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                </div>
                <div class="form-group">
                  <label class="mb-5 font-sm color-gray-700">Email *</label>
                  <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  tran('Email') }}" name="email" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                </div>

               
               
                <div class="form-group">
                  <label class="mb-5 font-sm color-gray-700">Password *</label>
                  <input type="password" class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" placeholder="{{  tran('Password') }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                </div>
                <div class="form-group">
                  <label class="mb-5 font-sm color-gray-700" required>{{tran('Re-Password')}} *</label>
                  <input type="password" class="form-control rounded-0" placeholder="{{  tran('Confirm Password') }}" name="password_confirmation" required>
                </div>
                <div class="form-group">
                  <label>
                    <input class="checkagree" type="checkbox"  required>{{tran('By clicking Register button, you agree our terms and policy,')}}
                  </label>
                </div>
                <div class="form-group">
                  <input class="font-md-bold btn btn-buy" type="submit" value="{{tran('Create Your Shop')}}">
                </div>
              </div>
            </div>
            <div class="col-lg-5">
              
            
            <div class="box-login-social pt-65 pl-50">
                <h5 class="text-center">{{tran('Basic Shop Information')}}</h5>
               
                <div class="p-3">
                            <div class="form-group">
                                <label>{{ tran('Shop Name')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" value="{{ old('shop_name') }}" placeholder="{{ tran('Shop Name')}}" name="shop_name" required>
                                @if ($errors->has('shop_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('shop_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ tran('Address')}} <span class="text-primary">*</span></label>
                                <input type="text" class="form-control mb-3 rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{ tran('Address')}}" name="address" required>
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>              
            
            </div>
            
            
            </div>
          </div>
        </div>
        </form>
      </section>
@endsection
