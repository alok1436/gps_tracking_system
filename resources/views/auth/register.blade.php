@extends('layouts.app')
<style>
#map {
    height: 400px;
    width: 100%;
    margin-top: 20px;
}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select name="role" required id="role" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Driver">Driver</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="autocomplete" class="col-md-4 col-form-label text-md-end">{{ __('Enter your location') }}</label>

                            <div class="col-md-6">
                                <input id="autocomplete" type="text" class="form-control" name="location" required autocomplete="location">
                                <input type="hidden" id="latitude" name="latitude" />
                                <input type="hidden" id="longitude" name="longitude" />

                                <div id="map"></div>
                            </div>
                        </div>
                        
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var map;
    var marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 0, lng: 0},
            zoom: 8
        });
        marker = new google.maps.Marker({
            map: map,
            draggable: true // Allow marker to be dragged
        });

        marker.addListener('dragend', function() {
        updateMarkerPosition(marker.getPosition());
        });

    
        map.addListener('click', function(event) {
        marker.setPosition(event.latLng);
        updateMarkerPosition(marker.getPosition());
        });
    }

   

    function initAutocomplete() {
      var input = document.getElementById('autocomplete');
      var autocomplete = new google.maps.places.Autocomplete(input);

      autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();

      map.setCenter({lat: lat, lng: lng});
      marker.setPosition({lat: lat, lng: lng});

      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lng;
    });
    }

    function updateMarkerPosition(latLng) {
        document.getElementById('latitude').value = latLng.lat();
        document.getElementById('longitude').value = latLng.lng();
    }
    
    google.maps.event.addDomListener(window, 'load', function() {
        initMap();
        initAutocomplete();
    });
  </script>
@endsection
