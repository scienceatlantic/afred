<?php

namespace App\Listeners;

use App\WordPress;
use App\Events\ListingEventCompleted;
use App\Events\ListingHidden;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HideListingInWordpress implements ShouldQueue
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
     * @param  ListingHidden  $event
     * @return void
     */
    public function handle(ListingHidden $event)
    {
        WordPress::hideListing(
            $event->listing->targetDirectory,
            $event->listing->wp_post_id
        );

        event(new ListingEventCompleted($event->formEntry, 'ListingHidden'));
    }
}
