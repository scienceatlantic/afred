<?php

namespace App\Events;

use App\FacilityEditRequest;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FacilityEditTokenRequestedEvent extends Event
{
    use SerializesModels;
    
    public $fer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FacilityEditRequest $fer)
    {
        $this->fer = $fer;
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
