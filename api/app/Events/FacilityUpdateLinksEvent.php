<?php

namespace App\Events;

// Events.
use App\Events\Event;

// Laravel.
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models.
use App\FacilityUpdateLink;

class FacilityUpdateLinksEvent extends Event
{
    use SerializesModels;
    
    public $ful;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FacilityUpdateLink $ful)
    {
        $this->ful = $ful;
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
