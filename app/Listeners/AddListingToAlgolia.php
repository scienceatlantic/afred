<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\FormEntryStatusUpdated;
use App\Events\ListingAddedToWordpress;
use App\Events\ListingEventCompleted;
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

        event(new ListingEventCompleted($event->formEntry, 'ListingCreated'));
    }
}
