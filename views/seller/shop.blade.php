@extends('seller.layouts.app')

@section('content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ tran('Shop Setting')}}
                <span class="ml-3 fs-13">(<a href="{{ route('shop.visit', $shop->slug) }}" class="btn btn-link btn-sm px-0" target="_blank">{{ tran('Visit Shop')}} <i class="las la-arrow-right"></i></a>)</span>
            </h1>
        </div>
      </div>
    </div>

    <!-- Basic Info -->
    <div class="card">
    @include('admin.includes.alerts.success')
    @include('admin.includes.alerts.errors')
        <div class="card-header">
            <h5 class="mb-0 h6">{{ tran('Basic Info') }}</h5>
        </div>
        <div class="card-body">
            <form class="" action="{{ route('shop.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                @csrf
                <div class="row">
                    <label class="col-md-2 col-form-label">{{ tran('Shop Name') }}<span class="text-danger text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control mb-3" placeholder="{{ tran('Shop Name')}}" name="name" value="{{ $shop->name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Shop Logo') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <input type="file" name="logo" value="{{ $shop->logo }}" class="selected-files">
                            @error('logo')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                           @if($shop->logo !="")<img src="{{asset('assets/images/shops/'.$shop->logo)}}"  width="100" height="100">@endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>{{ tran('Shop Phone') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" class="form-control mb-3" placeholder="{{ tran('Phone')}}" name="phone" value="{{ $shop->phone }}" required>
                    </div>
                </div>

                            <!-- Country -->
                            <div class="row">
                            <div class="col-md-2">
                                <label>{{ tran('Country')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ tran('Select your country') }}" name="country_id" id="country_id" required>
                                        <option value="">{{ tran('Select your country') }}</option>
                                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}" @if($country->id == $shop->country_id) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ tran('State')}}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" id="state_id" required>
                               @if(isset($states) && $states->count() > 0)
                                @foreach ($states as $key => $state)
                        <option value="{{ $state->id }}" @if($shop->state_id == $state->id) selected @endif>
                            {{ $state->name }}
                        </option>
                    @endforeach
                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ tran('City')}}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" id="city_id" required>
                                @if(isset($cities) && $cities->count() > 0)
                                @foreach ($cities as $key => $city)
                        <option value="{{ $city->id }}" @if($shop->city_id == $city->id) selected @endif>
                            {{ $city->name }}
                        </option>
                    @endforeach
                    @endif
                                </select>
                            </div>
                        </div>


                <div class="row">
                    <label class="col-md-2 col-form-label">{{ tran('Shop Address') }} <span class="text-danger text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control mb-3" placeholder="{{ tran('Address')}}" name="address"  id="address"  value="{{ $shop->address }}" required>
                    </div>
                </div>
                @if (get_setting('shipping_type') == 'seller_wise_shipping')
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ tran('Shipping Cost')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" lang="en" min="0" class="form-control mb-3" placeholder="{{ tran('Shipping Cost')}}" name="shipping_cost" value="{{ $shop->shipping_cost }}" required>
                        </div>
                    </div>
                @endif 
                <div class="row">
                    <label class="col-md-2 col-form-label">{{ tran('Meta Title') }}<span class="text-danger text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control mb-3" placeholder="{{ tran('Meta Title')}}" name="meta_title" value="{{ $shop->meta_title }}" required>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 col-form-label">{{ tran('Meta Description') }}<span class="text-danger text-danger">*</span></label>
                    <div class="col-md-10">
                        <textarea name="meta_description" rows="3" class="form-control mb-3" required>{{ $shop->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{tran('Save')}}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delivery Boy Pickup Point -->
    @if (feature_is_activated('delivery_boy'))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ tran('Delivery Boy Pickup Point') }}</h5>
            </div>
            <div class="card-body">
                <form class="" action="{{ route('shop.update') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @csrf

                    @if (get_setting('google_map') == 1)
                        <div class="row mb-3">
                            <input id="searchInput" class="controls" type="text" placeholder="{{tran('Enter a location')}}">
                            <div id="map"></div>
                            <ul id="geoData">
                                <li style="display: none;">{{ tran('Full Address') }}: <span id="location"></span></li>
                                <li style="display: none;">{{ tran('Postal Code') }}: <span id="postal_code"></span></li>
                                <li style="display: none;">{{ tran('Country') }}: <span id="country"></span></li>
                                <li style="display: none;">{{ tran('Latitude') }}: <span id="lat"></span></li>
                                <li style="display: none;">{{ tran('Longitude') }}: <span id="lon"></span></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ tran('Longitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="longitude" name="delivery_pickup_longitude" readonly="" value="{{ $shop->delivery_pickup_longitude }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ tran('Latitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="latitude" name="delivery_pickup_latitude" readonly="" value="{{ $shop->delivery_pickup_latitude }}">
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ tran('Longitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="longitude" name="delivery_pickup_longitude" value="{{ $shop->delivery_pickup_longitude }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" id="">
                                <label for="exampleInputuname">{{ tran('Latitude') }}</label>
                            </div>
                            <div class="col-md-10" id="">
                                <input type="text" class="form-control mb-3" id="latitude" name="delivery_pickup_latitude" value="{{ $shop->delivery_pickup_latitude }}">
                            </div>
                        </div>
                    @endif

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{tran('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Banner Settings -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ tran('Banner Settings') }}</h5>
        </div>
        <div class="card-body">
            <form class="" action="{{ route('shop.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                @csrf
                <!-- Top Banner -->
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Top Banner') }} (1920x360)</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <input type="file" name="top_banner" value="{{ $shop->top_banner }}" class="selected-files">
                            @error('top_banner')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                            @if($shop->top_banner !="")<img src="{{asset('assets/images/shops/'. $shop->top_banner)}}" width="100" height="100">@endif
                        </div>
                        <small class="text-muted">{{ tran('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>
                    </div>
                </div>
                <!-- Slider Banners -->
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Slider Banners') }} (1500x450)</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <input multiple type="file" name="sliders[]"  class="selected-files">
                            @error('sliders.*')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                        @if($shop->sliders)
                        @foreach (explode(',',$shop->sliders) as $key => $banner)
                        @if($banner !="")<img src="{{asset('assets/images/shops/'. $banner)}}" width="100" height="100">@endif
                        @endforeach
                        @endif
                    </div>
                        <small class="text-muted">{{ tran('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>
                    </div>
                </div>
                <!-- Banner Full width 1 -->
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Banner Full width 1') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <input type="file" multiple  name="banner_full_width_1[]">
                            @error('banner_full_width_1.*')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                        @if($shop->banner_full_width_1)
                        @foreach (explode(',',$shop->banner_full_width_1) as $key => $banner)
                        @if($banner !="")<img src="{{asset('assets/images/shops/'.  $banner)}}" width="100" height="100">@endif
                        @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                <!-- Banners half width -->
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Banners half width') }} ({{ tran('2 Equal Banners') }})</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <input type="file" name="banners_half_width[]" multiple   class="selected-files">
                            @error('banners_half_width.*')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                        @if($shop->banners_half_width)
                        @foreach (explode(',',$shop->banners_half_width) as $key => $banner)
                        @if($banner !="")<img src="{{asset('assets/images/shops/'. $banner)}}" width="100" height="100">@endif
                        @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                <!-- Banner Full width 2 -->
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">{{ tran('Banner Full width 2') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <input type="file" name="banner_full_width_2[]"  multiple   class="selected-files">
                            @error('banner_full_width_2.*')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                        @if($shop->banner_full_width_2)
                        @foreach (explode(',',$shop->banner_full_width_2) as $key => $banner)
                        @if($banner !="")<img src="{{asset('assets/images/shops/'. $banner)}}" width="100" height="100">@endif
                        @endforeach
                        @endif
                    </div>
                    </div>
                </div>
                <!-- Save Button -->
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{tran('Save')}}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Social Media Link -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ tran('Social Media Link') }}</h5>
        </div>
        <div class="card-body">
            <form class="" action="{{ route('shop.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                @csrf
                <div class="form-box-content p-3">
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">{{ tran('Facebook') }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="{{ tran('Facebook')}}" name="facebook" value="{{ $shop->facebook }}">
                            <small class="text-muted">{{ tran('Insert link with https ') }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">{{ tran('Instagram') }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="{{ tran('Instagram')}}" name="instagram" value="{{ $shop->instagram }}">
                            <small class="text-muted">{{ tran('Insert link with https ') }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">{{ tran('Twitter') }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="{{ tran('Twitter')}}" name="twitter" value="{{ $shop->twitter }}">
                            <small class="text-muted">{{ tran('Insert link with https ') }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">{{ tran('Google') }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="{{ tran('Google')}}" name="google" value="{{ $shop->google }}">
                            <small class="text-muted">{{ tran('Insert link with https ') }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">{{ tran('Youtube') }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="{{ tran('Youtube')}}" name="youtube" value="{{ $shop->youtube }}">
                            <small class="text-muted">{{ tran('Insert link with https ') }}</small>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{tran('Save')}}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

<script> 
         
$(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
           
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        
        $(document).on('change', '[name=city_id]', function() {
            var city_id = $(this).val();
            get_region(city_id);
        });
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

       

        $(document).on('change', '[name=city_id]', function() {
            var country_id = $(this).val();
             var country= $("#country_id option:selected").text().trim();
             var state =$("#state_id option:selected").text().trim();
            var city=  $("#city_id option:selected").text().trim();
         
          $("#address").val(country +','+state +','+city);
        });
    </script>
    @if (feature_is_activated('delivery_boy') && get_setting('google_map') == 1)
            
    <script>
        function initialize(id_format = '') {
            let default_longtitude = '';
            let default_latitude = '';
            @if (get_setting('google_map_longtitude') != '' && get_setting('google_map_longtitude') != '')
                default_longtitude = {{ get_setting('google_map_longtitude') }};
                default_latitude = {{ get_setting('google_map_latitude') }};
            @endif

            var lat = -33.8688;
            var long = 151.2195;

            if (document.getElementById('latitude').value != '' &&
                document.getElementById('longitude').value != '') {
                lat = parseFloat(document.getElementById('latitude').value);
                long = parseFloat(document.getElementById('longitude').value);
            } else if (default_longtitude != '' &&
                default_latitude != '') {
                lat = default_latitude;
                long = default_longtitude;
            }


            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: lat,
                    lng: long
                },
                zoom: 13
            });

            var myLatlng = new google.maps.LatLng(lat, long);

            var input = document.getElementById(id_format + 'searchInput');
            // console.log(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                anchorPoint: new google.maps.Point(0, -29),
                draggable: true,
            });

            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                infowindow.open(map, marker);
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                /*
                marker.setIcon(({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                */
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                //Location details
                for (var i = 0; i < place.address_components.length; i++) {
                    if (place.address_components[i].types[0] == 'postal_code') {
                        document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
                    }
                    if (place.address_components[i].types[0] == 'country') {
                        document.getElementById('country').innerHTML = place.address_components[i].long_name;
                    }
                }
                document.getElementById('location').innerHTML = place.formatted_address;
                document.getElementById(id_format + 'latitude').value = place.geometry.location.lat();
                document.getElementById(id_format + 'longitude').value = place.geometry.location.lng();
            });

        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=places&language=en&callback=initialize" async defer></script>
    @endif
@endpush
