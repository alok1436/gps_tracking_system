@extends('layouts.app')
<style>
  #map {
    height: 300px;
    width: 100%;
  }
</style>
@section('content')
 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if($ride->status == 'Progress')
                        {{ __('Your ride is on the way') }}
                        <a href="{{ route('ride.complete',['hash'=>$ride->hash]) }}" >Mark as complete</a>
                    @endif
                </div>
                
                <div class="card-body">
                    @if($ride->status == 'Completed')
                        <div class="alert alert-success" role="alert">
                            You have done your ride,  <a href="{{ route('rides') }}" >View list</a>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>

    function loadRide(){
        $.ajax({
                url: "{{ route('ride.get',['hash'=>request()->hash]) }}",
                method: 'post',
                data: {
                  id: "{{ request()->hash }}",
                  _token:"{{ csrf_token() }}"
                },
                success: function(response){
                    drawMapAndLine(response.data);
                },
                error: function(xhr, status, error){
                    
                }
          });
    }

    loadRide();

    var map, midpointMarker;
   
    function drawMapAndLine(data) {

            r = data.location;

            var point1 = { lat: r.source.lat, lng: r.source.lng };
            var point2 = { lat: r.destination.lat, lng: r.destination.lng };

            console.log(point1);
            // Initialize the map
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: point1
            });

            var directionsService = new google.maps.DirectionsService();

            // Request directions
            var request = {
                origin: point1,
                destination: point2,
                travelMode: 'DRIVING'
            };

            // Get multiple routes
            directionsService.route(request, function(response, status) {
                if (status === 'OK') {
                  console.log(response);
                  console.log(response.routes);
                    // Loop through each route
                    for (var i = 0; i < response.routes.length; i++) {
                        // Draw the line for each route
                        var directionsRenderer = new google.maps.DirectionsRenderer({
                            map: map,
                            directions: response,
                            routeIndex: i
                        });
                    }
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
           
            var midpoint = {
                lat: Number(r.current.lat),
                lng: Number(r.current.lng),
            };
            console.log('cc', midpoint);
            midpointMarker = new google.maps.Marker({
                position: midpoint,
                map: map,
                title: 'Midpoint'
            });
        }
        
       //update the location when channel subscribe
        var pusher =  new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
        })
        var channel = pusher.subscribe('location{{ $ride->hash }}');

        channel.bind('driver-location-updated', function(data) {

        if(midpointMarker){
            midpointMarker.setMap(null);
        }
        var midpoint = {
            lat: Number(data.location.lat),
            lng: Number(data.location.lng)
        };
        console.log('data',midpoint);
         
        midpointMarker = new google.maps.Marker({
            position: midpoint,
            map: map,
            title: 'Midpoint'
        });

            $.ajax({
                url: "{{ route('ride.update.current.location') }}",
                method: 'post',
                data: data,
                success: function(response){
                    
                },
                error: function(xhr, status, error){
                    
                }
            });

        });
  </script>

@endsection
