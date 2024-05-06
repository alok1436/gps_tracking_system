<?php

namespace App\Observers;

use App\Models\Ride;
use App\Notifications\DriverDeparted;
class RideObserver
{
    /**
     * Handle the Ride "created" event.
     */
    public function created(Ride $ride): void
    {
        $ride->company->notify((new DriverDeparted($ride))->onQueue('notifications'));
    }

    /**
     * Handle the Ride "updated" event.
     */
    public function updated(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "deleted" event.
     */
    public function deleted(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "restored" event.
     */
    public function restored(Ride $ride): void
    {
        //
    }

    /**
     * Handle the Ride "force deleted" event.
     */
    public function forceDeleted(Ride $ride): void
    {
        //
    }
}
