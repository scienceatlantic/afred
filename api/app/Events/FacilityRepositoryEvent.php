<?php

namespace App\Events;

// Events.
use App\Events\Event;

// Laravel.
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models.
use App\FacilityRepository;
use App\FacilityUpdateLink;

class FacilityRepositoryEvent extends Event
{
    use SerializesModels;
    
    public $fr;
    public $ful;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FacilityRepository $fr, FacilityUpdateLink $ful)
    {
        $this->fr = $fr;
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
