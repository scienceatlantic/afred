<?php

namespace App\Events;

// Events.
use App\Events\Event;

// Laravel.
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models.
use App\User;

class ReportEvent extends Event
{
    use SerializesModels;
    
    public $user;
    
    public $report;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Array $report)
    {
        $this->user = $user;
        $this->report = $report;
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
