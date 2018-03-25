<?php

namespace App\Events;

use App\FormEntry;
use App\Listing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ListingUnhidden
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $formEntry;

    public $listing;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FormEntry $formEntry, Listing $listing)
    {
        $this->formEntry = $formEntry;
        $this->listing = $listing;
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
