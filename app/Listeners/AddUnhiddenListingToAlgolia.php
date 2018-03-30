<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\ListingEventCompleted;
use App\Events\ListingUnhidden;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUnhiddenListingToAlgolia implements ShouldQueue
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
        Algolia::addListing($event->formEntry, $event->listing);

        event(new ListingEventCompleted($event->formEntry, 'ListingUnhidden'));
    }
}
