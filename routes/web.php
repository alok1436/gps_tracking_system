<?php
use App\Http\Controllers\RideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Route::get('/testMail', function () {
    Mail::to('abc@gmail.com')->send(new TestMail());
    return 'Test email sent successfully!';
});

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/update/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [HomeController::class, 'editProfile'])->name('profile.edit');
});

Route::middleware(['role:Driver'])->group(function () {
    Route::get('/ride/details', [RideController::class, 'details'])->name('ride.details');
    Route::post('/ride/confirm', [RideController::class, 'confirm'])->name('ride.confirm');
    Route::get('/ride/{hash}', [RideController::class, 'show'])->name('ride.show');
    Route::post('/ride/get/{hash}', [RideController::class, 'get'])->name('ride.get');
    Route::post('/update/ride/location', [RideController::class, 'updateRideCurrentLocation'])->name('ride.update.current.location');
    Route::get('/ride/complete/{hash}', [RideController::class, 'markAsComplete'])->name('ride.complete');

    Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
    Route::post('vehicle/store', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::get('vehicle/delete/{vehicle}', [VehicleController::class, 'delete'])->name('vehicle.delete');
    Route::get('vehicle/mark/primary/{vehicle}', [VehicleController::class, 'markPrimary'])->name('vehicle.mark.primary');

    Route::get('rides', [RideController::class, 'driverRides'])->name('rides')->middleware('role:Driver');
});