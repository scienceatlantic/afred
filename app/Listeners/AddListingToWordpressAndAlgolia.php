<?php

namespace App\Listeners;

use App\Algolia;
use App\Listing;
use App\WordPress;
use App\Events\FormEntryUpdate;
use App\Events\ListingCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddListingToWordpressAndAlgolia implements ShouldQueue
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
        Algolia::addListing(
            $event->formEntry,
            Wordpress::addListing($event->listing)
        );

        $isLastListing = !$event
            ->formEntry
            ->listings()
            ->where('is_in_wp', false)
            ->orWhere('is_in_algolia', false)
            ->count();

        // Invalidate cache if there are no more listings that need to be added
        // to either WordPress or Algolia.
        if ($isLastListing) {
            $event->formEntry->is_cache_valid = false;
            $event->formEntry->update();
            event(new FormEntryUpdate($event->formEntry));
        }
    }
}
