<?php

namespace App\Listeners;

use App\WordPress;
use App\Events\ListingDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteListingFromWordpress implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ListingDeleted  $event
     * @return void
     */
    public function handle(ListingDeleted $event)
    {
        WordPress::deleteListing($event->targetDirectory, $event->wpPostId);
    }
}
