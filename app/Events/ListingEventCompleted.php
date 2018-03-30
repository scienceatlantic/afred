<?php

namespace App\Events;

use App\FormEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ListingEventCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $formEntry;

    public $eventName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FormEntry $formEntry, $eventName)
    {
        $this->formEntry = $formEntry;
        $this->eventName = $eventName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
