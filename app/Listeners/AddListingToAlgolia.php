<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\FormEntryStatusUpdated;
use App\Events\ListingAddedToWordpress;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddListingToAlgolia implements ShouldQueue
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
     * @param  ListingAddedToWordpress  $event
     * @return void
     */
    public function handle(ListingAddedToWordpress $event)
    {
        Algolia::addListing($event->formEntry, $event->listing);

        // Send out status updated email if all listings have been added to
        // WordPress and Algolia.
        $allListingsAdded = !$event
            ->formEntry
            ->listings()
            ->where('is_in_wp', false)
            ->orWhere('is_in_algolia', false)
            ->count();
        
        if ($allListingsAdded) {
            event(new FormEntryStatusUpdated($event->formEntry));
        }        
    }
}
