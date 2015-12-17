<?php

namespace App\Events;

use App\FacilityRevisionHistory;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FacilityRevisionHistoryEvent extends Event
{
    use SerializesModels;
    
    public $frh;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FacilityRevisionHistory $frh)
    {
        $this->frh = $frh;
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
