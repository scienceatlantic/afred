<?php

namespace App\Listeners;

use App\Algolia;
use App\Events\ListingEventCompleted;
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

        event(new ListingEventCompleted($event->formEntry, 'ListingDeleted'));
    }
}
