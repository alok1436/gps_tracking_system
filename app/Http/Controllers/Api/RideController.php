<?php

namespace App\Http\Controllers\Api;
use App\Events\UpdateDriverLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
class RideController extends Controller
{
    public function updateDriverLocation(Request $request){
        
        $ridehash = $request->ridehash;
        
        $ride = Ride::where(['hash'=>$ridehash])->first();

        if(!$ride){
            return response()->json(['success'=>true,'message'=>'Invalid ride'], 400);
        }

        if($ride->status == 'Completed'){
            return response()->json(['success'=>true,'message'=>'Ride already has been completed'], 400);
        }

        $location = ['lat'=> $request->get('lat'), 'lng'=> $request->get('lng')];
        event( new UpdateDriverLocation($location, $ridehash));
        return response()->json(['success'=>'location updated']);
    }
}
