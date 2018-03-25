<?php

namespace App\Listeners;

use App\WordPress;
use App\Events\ListingAddedToWordpress;
use App\Events\ListingCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddListingToWordpress implements ShouldQueue
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
     * @param  ListingCreated  $event
     * @return void
     */
    public function handle(ListingCreated $event)
    {
        $listing = Wordpress::addListing($event->listing);

        event(new ListingAddedToWordpress($event->formEntry, $listing));
    }
}
