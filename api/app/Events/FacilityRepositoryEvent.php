<?php

namespace App\Events;

use App\FacilityRepository;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
