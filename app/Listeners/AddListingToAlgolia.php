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

        $isLastListing = !$event
            ->formEntry
            ->listings()
            ->where('is_in_wp', false)
            ->orWhere('is_in_algolia', false)
            ->count();

        // Invalidate cache if there are no more listings that need to be added
        // to either WordPress or Algolia.
        if ($isLastListing) {
            $event->formEntry->refreshCache();
            event(new FormEntryStatusUpdated($event->formEntry));
        }
    }
}
