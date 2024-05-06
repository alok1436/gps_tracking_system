@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Vehicles') }}
                    <a href="javascript:void(0)" class="btn btn-primary addVehicle" data-bs-toggle="modal" data-bs-target="#exampleModal">Add vehicle</a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Vehicle number</th>
                            <th scope="col">Milage</th>
                            <th scope="col">Fuel consumption</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <th scope="row">#{{ $vehicle->id }}</th>
                                <td>{{ $vehicle->vehicle_number }}</td>
                                <td>{{ $vehicle->milage }}</td>
                                <td>{{ $vehicle->fuel_consumption }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-primary editVehicle" data-bs-toggle="modal" data-bs-target="#exampleModal" data-vehicle='{{ json_encode($vehicle) }}'>Edit</a>
                                    <a href="{{ route('vehicle.delete',['vehicle'=>$vehicle]) }}" class="btn btn-danger" onclick="return confirm('Are you sure?')" >Delete</a>
                                    @if($vehicle->id != $user->vehicle_id)
                                        <a href="{{ route('vehicle.mark.primary',['vehicle'=>$vehicle]) }}" class="btn btn-warning">Mark primary</a>
                                    @endif
                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form" action="{{ route('vehicle.store') }}">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Vehicle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    @csrf
                    <label for="vehicle_number" class="form-label">Vehicle number</label>
                    <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" required>
                </div>
                
                <div class="mb-3">
                    <label for="milage" class="form-label">Milage</label>
                    <input type="text" class="form-control" name="milage" id="milage" required>
                    <input type="hidden" class="form-control" name="id" id="vid">
                </div>

                <div class="mb-3">
                    <label for="fuel_consumption" class="form-label">Fuel consumption</label>
                    <input type="text" name="fuel_consumption" class="form-control" id="fuel_consumption" required>
                </div>

                <div class="mb-3">
                    <label for="driving_behaviour" class="form-label">Driving behaviour</label>
                    <input type="text" name="driving_behaviour" class="form-control" id="driving_behaviour" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </form>
  </div>
@endsection
