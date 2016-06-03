<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\EmailEvent' => [
            'App\Listeners\EmailListener'
        ],
        'App\Events\FacilityRepositoryEvent' => [
            'App\Listeners\FacilityRepositoryListener',            
        ],
        'App\Events\FacilityUpdateLinksEvent' => [
            'App\Listeners\FacilityUpdateLinksListener'
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
