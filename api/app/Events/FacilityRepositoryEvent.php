<?php

namespace App\Events;

// Events.
use App\Events\Event;

// Laravel.
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models.
use App\FacilityRepository;

class FacilityRepositoryEvent extends Event
{
    use SerializesModels;
    
    public $fr;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FacilityRepository $fr)
    {
        $this->fr = $fr;
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
