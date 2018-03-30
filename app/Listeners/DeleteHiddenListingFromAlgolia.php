<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\ListingEventCompleted;
use App\Events\ListingHidden;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteHiddenListingFromAlgolia implements ShouldQueue
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
        Algolia::deleteListing(
            $event->listing->formSection,
            $event->listing->published_entry_section_id
        );

        event(new ListingEventCompleted($event->formEntry, 'ListingHidden'));
    }
}
