<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Directory'       => 'App\Policies\DirectoryPolicy',
        'App\Form'            => 'App\Policies\FormPolicy',
        'App\FormEntry'       => 'App\Policies\FormEntryPolicy',
        'App\FormEntryStatus' => 'App\Policies\FormEntryStatusPolicy',
        'App\FormEntryToken'  => 'App\Policies\FormEntryTokenPolicy',
        'App\User'            => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
