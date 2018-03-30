<?php

namespace App\Listeners;

use App\Events\ListingEventCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// Note: Not meant to be queued (i.e. not implement ShouldQueue)
class RefreshFormEntryCache
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
     * @param  ListingEventCompleted  $event
     * @return void
     */
    public function handle(ListingEventCompleted $event)
    {
        switch($event->eventName) {
            case 'ListingCreated':
                // Cache needs to be refreshed only after all listings have been
                // created.
                $numListingsToBeAdded = $event
                    ->formEntry
                    ->listings()
                    ->where('is_in_wp', false)
                    ->orWhere('is_in_algolia', false)
                    ->count();
                
                if ($numListingsToBeAdded === 0) {
                    $event->formEntry->refreshCache();
                }
                break;
            case 'ListingUnhidden':
                $numListingsToUnhide = $event
                    ->formEntry
                    ->listings()
                    ->where('is_in_wp', false)
                    ->count();

                if ($numListingsToUnhide === 0) {
                    $event->formEntry->refreshCache();
                }
                break;
            case 'ListingDeleted':
            case 'ListingHidden':
                // Do nothing
                break;                                    
        }
    }
}
