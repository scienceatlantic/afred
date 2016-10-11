<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function getSuperAdmin()
    {
        $role = App\Role::where('name', 'SUPER_ADMIN')->first();
        $user = factory(App\User::class)->create();
        $user->roles()->attach([$role->id]);
        return $user;
    }

    protected function getAdmin()
    {
        $role = App\Role::where('name', 'ADMIN')->first();
        $user = factory(App\User::class)->create();
        $user->roles()->attach([$role->id]);
        return $user;
    }
}
