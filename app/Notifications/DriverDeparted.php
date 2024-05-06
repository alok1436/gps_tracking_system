<?php

namespace App\Notifications;
use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DriverDeparted extends Notification implements ShouldQueue
{
    use Queueable;

    public $ride;
    /**
     * Create a new notification instance.
     */
    public function __construct(Ride $ride)
    {
        $this->ride = $ride;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->ride->driver->primaryVehicle;
        $lineMessage = "Driver {$this->ride->driver->name} is on the way. His vehicle no is. {$vehicle->vehicle_number}" ;
        return (new MailMessage)
                    ->line($lineMessage)
                    ->action('View ride', url('ride/'.$this->ride->hash))
                    ->line('Thank you for using our GPS locater!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
