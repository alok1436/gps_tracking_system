<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function details(Request $request){
        $user = Auth::user();
        $company = User::find($request->id);
        $distance = $user->calculateDistance($company->lat, $company->lng);
        $text = sprintf("Your ride is from %s to %sand the total distance will be %f km.",$user->location,$company->location,$distance);
        return response()->json(['sucess'=> 1, 'data'=> ['text'=> $text, 'l1'=>['lat'=>$user->lat,'lng'=>$user->lng,], 'l2'=>['lat'=>$company->lat,'lng'=>$company->lng,]]]);
    }

 
    public function confirm(Request $request){
        $user = Auth::user();
        $company = User::find($request->id);
 
        $ride = new Ride;
        $ride->hash = uniqid();
        $ride->driver_id = $user->id;
        $ride->company_id = $company->id;
        $ride->save();

        $ride->location()->create([
            'source'        => json_encode(['location'=>$user->location, 'lat'=> $user->lat, 'lng'=> $user->lng]),
            'destination'   => json_encode(['location'=>$company->location, 'lat'=> $company->lat, 'lng'=> $company->lng]),
            'current'       => json_encode(['location'=>$user->location, 'lat'=> $user->lat, 'lng'=> $user->lng]),
            'vehicle_info'  => json_encode($user->primaryVehicle->toArray()),
        ]);

        return response()->json(['sucess'=> 1, 'data'=> $ride, 'redirect'=> route('ride.show', $ride->hash)]);
    }

    public function show(Request $request){
        $ride = Ride::where('hash', $request->hash)->first();
        if(! $ride) abort(404);
        return view('rides.show', compact('ride'));
    }

    public function get(Request $request){
        $ride = Ride::with('location')->where('hash', $request->hash)->first();
        return response()->json(['sucess'=> 1, 'data'=>$ride]);
    }

    public function driverRides(Request $request){
        $rides = Auth::user()->rides()->whereHas('location')->orderBy('created_at','desc')->paginate(100);
        return view('rides.list', compact('rides'));
    }

    public function markAsComplete(Request $request){
        $ride = Ride::where('hash', $request->hash)->first();
        if(! $ride) abort(404);
        
        $ride->status = 'Completed';
        $ride->completed_at = \Carbon\Carbon::now();
        $ride->save();
        return redirect()->back()->with('status','Your ride has been finished');
    }

    public function updateRideCurrentLocation(Request $request){
        $ride = Ride::where('hash', $request->hash)->first();
        if(! $ride) abort(404);
        $current = ['lat'=> $request->location['lat'],'lng'=> $request->location['lng']];
        $ride->location()->update(['current'=> json_encode($current)]);
        return response()->json(['status' => 'Your ride location has been updated']);
    }
}
