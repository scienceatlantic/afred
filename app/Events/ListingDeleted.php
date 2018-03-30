<?php

namespace App\Events;

use App\Directory;
use App\FormEntry;
use App\FormSection;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ListingDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $formEntryId;

    public $targetDirectory;

    public $targetFormSection;

    public $formEntry;

    public $wpPostId;

    public $publishedEntrySectionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Directory $targetDirectory,
        FormSection $targetFormSection,
        FormEntry $formEntry,
        $wpPostId,
        $publishedEntrySectionId
    ) {
        $this->formEntryId = $formEntry->id;
        $this->targetDirectory = $targetDirectory;
        $this->targetFormSection = $targetFormSection;
        $this->formEntry = $formEntry;
        $this->wpPostId = $wpPostId;
        $this->publishedEntrySectionId = $publishedEntrySectionId;
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
