<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\FormEntryStatusUpdated' => [
            'App\Listeners\EmailFormEntryStatusUpdate'
        ],
        'App\Events\FormEntryTokenCreated' => [
            'App\Listeners\EmailFormEntryToken'
        ],
        'App\Events\FormReportRequested' => [
            'App\Listeners\EmailFormReport'
        ],        
        'App\Events\ListingCreated' => [
            'App\Listeners\AddListingToWordpress',
        ],
        'App\Events\ListingAddedToWordpress' => [
            'App\Listeners\AddListingToAlgolia'
        ],
        'App\Events\ListingDeleted' => [
            'App\Listeners\DeleteListingFromWordpress',
            'App\Listeners\DeleteListingFromAlgolia'
        ],
        'App\Events\ListingHidden' => [
            'App\Listeners\HideListingInWordpress',
            'App\Listeners\DeleteHiddenListingFromAlgolia'
        ],
        'App\Events\ListingUnhidden' => [
            'App\Listeners\UnhideListingInWordpress',
            'App\Listeners\AddUnhiddenListingToAlgolia'
        ],
        'App\Events\ListingEventCompleted' => [
            'App\Listeners\RefreshFormEntryCache'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
