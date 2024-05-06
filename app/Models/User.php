<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\RoleUser;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lat',
        'lng',
        'location'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class,'driver_id', 'id');
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class,'driver_id', 'id');
    }

    public function primaryVehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class,'id', 'vehicle_id');
    }


    public function isCompany(): bool
    {
        if ($this->roles()->find(Role::COMPANY_ROLE)) {
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        if ($this->roles()->find(Role::ADMIN_ROLE)) {
            return true;
        }

        return false;
    }

    public function isDriver(): bool
    {
        if ($this->roles()->find(Role::DRIVER_ROLE)) {
            return true;
        }

        return false;
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function calculateDistance($lat2, $lon2) {
        // Radius of the Earth in kilometers
        $R = 6371; 
        
        $lat1 = $this->lat;
        $lon1 = $this->lng;

        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        // Calculate the change in coordinates
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        // Calculate the distance using the Haversine formula
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;
        
        return $distance; // Distance in kilometers
    }
}
