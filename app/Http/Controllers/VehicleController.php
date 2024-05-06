<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class VehicleController extends Controller
{
    public function index(){
        $user = Auth::user();
        $vehicles = $user->vehicles()->orderBy('created_at','DESC')->paginate(10);
        return view('vehicles.index', compact('vehicles','user'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'vehicle_number'  =>  'required',
            ]);
    
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }

        $vehicle = $request->id > 0 ? Vehicle::find($request->id) : new Vehicle();
        $vehicle->driver_id = Auth::id();
        foreach($request->all() as $key=>$value){
            if( in_array( $key,$vehicle->getFillable() ) ){
                $vehicle->$key = $value;
            }
        }
        $vehicle->save();
        return response()->json(['success'=>true,'message'=>'Saved.','redirect_url'=>route('vehicle.index')]);
    }

    public function delete(Vehicle $vehicle){
        $vehicle->delete();
        return redirect()->back();
    }

    public function markPrimary(Vehicle $vehicle){
        $user = Auth::user();
        $user->vehicle_id = $vehicle->id;
        $user->save();
        return redirect()->back();
    }
}
