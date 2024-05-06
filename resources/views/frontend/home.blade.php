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
                <div class="card-header">{{ __('Companies list') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="list-group">
                        @foreach ($users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $user->name }}  ({{ $user->location }})
                                @if(Auth::check() && Auth::user()->isDriver())
                                    <span class="badge bg-primary rounded-pill">
                                        <button class="btn btn-primary get-ride-details" data-bs-toggle="modal" data-bs-target="#mapModal" data-id="{{ $user->id }}">Start Ride</button>
                                    </span>
                                @endif
                          </li>
                        @endforeach
                      </ul>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mapModalLabel">Confirm Ride</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h5 id="text" style="margin-bottom:5px"></h5>
            <div id="map"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="confirmRide">Confirm ride</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>

    $('#confirmRide').on('click', function(){
          $.ajax({
                url: "{{ route('ride.confirm') }}",
                method: 'post',
                beforeSend:function(){
                  $("#confirmRide").attr('disabled', true).text('Please wait...');
                },
                data: {
                  id:$(this).data('id'),
                  _token:"{{ csrf_token() }}"
                },
                success: function(response){
                    location.href = response.redirect;
                },
                error: function(xhr, status, error){
                    
                },
                complete:function(){
                  $("#confirmRide").attr('disabled', false).text('Confirm ride');
                }
          });
      });

    var map;
    $('.get-ride-details').click(function(){
            // Make AJAX request
            $("#confirmRide").attr('data-id', $(this).data('id'));
            $.ajax({
                url: "{{ route('ride.details') }}",
                method: 'get', // or 'GET'
                data: {
                   id:$(this).data('id')
                },
                success: function(response){
                    // Handle the response from the server
                    $("#text").html(response.data.text);
                    var r = response.data;
                    drawMapAndLine(r);
                },
                error: function(xhr, status, error){
                    // Handle errors
                    console.error(error);
                }
        });
    });

    function drawMapAndLine(r) {
            var point1 = { lat: r.l1.lat, lng: r.l1.lng };
            var point2 = { lat: r.l2.lat, lng: r.l2.lng };

            console.log(point1);
            // Initialize the map
            var map = new google.maps.Map(document.getElementById('map'), {
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
        }
  </script>

@endsection
