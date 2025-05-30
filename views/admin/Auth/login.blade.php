<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.urbanui.com/melody/template/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Sep 2018 06:08:53 GMT -->
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('vendors/iconfonts/font-awesome/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.addons.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
  
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
          @include('admin.includes.alerts.errors')
          @include('admin.includes.alerts.success')
            <div class="auth-form-light text-left p-5">
            <div class="brand-logo">
            <center>
              <?php
            if(get_setting('header_logo') !=""){
									$img=asset('assets/images/website_settings/'.get_setting('header_logo'));
								}else $img=asset('front/assets/imgs/default.png');
                ?>
                <img src="{{$img}}" alt="logo">
              </div>
              </center>
              <h6 class="font-weight-light">{{tran('Login to your account')}}</h6>
              <form class="pt-3" action="{{route('admin.login')}}" method="post">
              @csrf
                <div class="form-group">
                  <input type="email" value="<?php if(isset($_COOKIE["dashboard_login"])) { echo $_COOKIE["dashboard_login"]; } ?>"  name="email"  class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="password" value="<?php if(isset($_COOKIE["dashboard_password"])) { echo $_COOKIE["dashboard_password"]; } ?>" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                <label class="form-check">
                <input class="form-check-input" type="checkbox" name="remember"><span class="form-check-label">{{tran('Remember Me')}}</span>
              </label>
              </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">{{tran('SIGN IN')}}</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('vendors/js/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/misc.js')}}"></script>
  <script src="{{asset('js/settings.js')}}"></script>
  <script src="{{asset('js/todolist.js')}}"></script>
  <!-- endinject -->
</body>


<!-- Mirrored from www.urbanui.com/melody/template/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Sep 2018 06:08:53 GMT -->
</html>
