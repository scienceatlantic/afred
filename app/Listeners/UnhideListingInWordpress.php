<?php

namespace App\Listeners;

use App\WordPress;
use App\Events\ListingUnhidden;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnhideListingInWordpress implements ShouldQueue
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
     * @param  ListingUnhidden  $event
     * @return void
     */
    public function handle(ListingUnhidden $event)
    {
        WordPress::unhideListing(
            $event->listing->targetDirectory,
            $event->listing->wp_post_id
        );
    }
}
