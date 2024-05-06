<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;


    public function company(){
        return $this->hasOne(User::class,'id','company_id');
    }

    public function driver(){
        return $this->hasOne(User::class,'id','driver_id');
    }

    public function location(){
        return $this->hasOne(RideLocation::class);
    }
}
