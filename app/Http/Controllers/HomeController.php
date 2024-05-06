<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){
            $q->where('name','Company');
        })->get();
        return view('frontend.home', compact('users'));
    }

    public function editProfile(Request $request){
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $user = Auth::user();
        $user->name = $request->name;
        $user->lat = $request->latitude;
        $user->lng = $request->longitude;
        $user->location = $request->location;
        $user->save();
        return redirect()->back()->with('status','Your profile has been updated.');
    }
}
