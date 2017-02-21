<?php

namespace App\Events;

// Events.
use App\Events\Event;

// Laravel.
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models.
use App\Facility;

class UpdateFacilityReminderEvent extends Event
{
    use SerializesModels;
    
    public $facility;

    public $interval;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Facility $facility, $interval)
    {
        $this->facility = $facility;
        $this->interval = $interval;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
