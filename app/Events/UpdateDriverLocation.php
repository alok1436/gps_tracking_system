<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateDriverLocation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location;
    
    public $ridehash;
    /**
     * Create a new event instance.
     */
    public function __construct($location, $ridehash)
    {
        $this->location = $location;

        $this->ridehash = $ridehash;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('location'.$this->ridehash)
        ];
    }

    public function broadcastWith()
    {
        return [
            'location' => $this->location,
            'hash' => $this->ridehash
        ];
    }


    public function broadcastAs(){
         return "driver-location-updated";
    }
}
