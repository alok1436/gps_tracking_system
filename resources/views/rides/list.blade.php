@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Your rides') }}
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">From/to</th>
                            <th scope="col">Company</th>
                            <th scope="col">Status</th>
                            <th scope="col">Started at</th>
                            <th scope="col">Completed at</th>
                            <th scope="col">Vehicle info</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($rides as $ride)
                            @php
                                $location = $ride->location;
                            @endphp
                            <tr>
                                <td>{{ $ride->hash }}</td>
                                <td>
                                    {{ $location->source->location }} to {{ $location->destination->location }}
                                </td>
                                <td>{{ $ride->company ? $ride->company->name : '' }}</td>
                                <td>{{ $ride->status }}</td>
                                <td>{{ $ride->created_at }}</td>
                                <td>{{ $ride->completed_at }}</td>
                                <td>
                                    @if($location->vehicle_info)
                                       V No.: {{ $location->vehicle_info->vehicle_number }}<br>
                                       Milage: {{ $location->vehicle_info->milage }}<br>
                                       Fuel consumption : {{ $location->vehicle_info->fuel_consumption }}<br>
                                    @endif
                                </td>
                                <td>
                                @if($ride->status == 'Progress')
                                    <a href="{{ route('ride.show',['hash'=>$ride->hash]) }}" class="btn btn-warning">View</a>
                                @endif
                            </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $rides->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
