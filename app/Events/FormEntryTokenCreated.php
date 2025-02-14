<?php

namespace App\Events;

use App\FormEntryToken;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FormEntryTokenCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;

    public $formEntry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FormEntryToken $token)
    {
        $this->token = $token;
        $this->formEntry = $token->beforeUpdateFormEntry;
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
