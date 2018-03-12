<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\ListingDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteListingFromAlgolia implements ShouldQueue
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
        Algolia::deleteListing(
            $event->targetFormSection,
            $event->publishedEntrySectionId
        );

        // Invalidate cache if there are no more listings attached to the form
        // entry.
        if (!$event->formEntry->listings()->count()) {
            $event->formEntry->is_cache_valid = false;
            $event->formEntry->update();
        }        
    }
}
