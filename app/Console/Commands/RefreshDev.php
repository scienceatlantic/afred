<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs `logs:clear`, `db:clear`, '
                           . '`migrate:refresh --seed`, `jobs:clear`, '
                           . '`algolia:clear`, and `wordpress:clear`';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('logs:clear');

        $this->call('db:clear');

        $this->call('migrate:refresh', [
            '--seed' => null
        ]);

        $this->call('jobs:clear');

        $this->call('algolia:clear');

        $this->call('wordpress:clear');
    }
}
