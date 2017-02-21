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
        'App\Events\EmailEvent' => [
            'App\Listeners\EmailListener'
        ],
        'App\Events\FacilityRepositoryEvent' => [
            'App\Listeners\FacilityRepositoryListener',            
        ],
        'App\Events\FacilityUpdateLinksEvent' => [
            'App\Listeners\FacilityUpdateLinksListener'
        ],
        'App\Events\ReportEvent' => [
            'App\Listeners\ReportListener'
        ],
        'App\Events\UpdateFacilityReminderEvent' => [
            'App\Listeners\UpdateFacilityReminderListener'
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
