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
        'App\Events\FormEntryUpdate' => [
            'App\Listeners\EmailFormEntryUpdate'
        ],
        'App\Events\ListingCreated' => [
            'App\Listeners\AddListingToWordpressAndAlgolia',
        ],
        'App\Events\ListingDeleted' => [
            'App\Listeners\DeleteListingFromWordpress',
            'App\Listeners\DeleteListingFromAlgolia'
        ],
        'App\Events\FormEntryTokenCreated' => [
            'App\Listeners\EmailFormEntryToken'
        ]
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
