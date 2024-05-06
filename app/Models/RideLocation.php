<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideLocation extends Model
{
    use HasFactory;

    protected $fillable = ['source','destination','current','vehicle_info'];

    public function getSourceAttribute($value){
        return $value ? json_decode($value) : [];
    }
    public function getDestinationAttribute($value){
        return $value ? json_decode($value) : [];
    }
    
    public function getCurrentAttribute($value){
        return $value ? json_decode($value) : [];
    }

    public function getVehicleInfoAttribute($value){
        return $value ? json_decode($value) : [];
    }
}