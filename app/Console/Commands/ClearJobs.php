<?php

namespace App\Console\Commands;

use App\Job;
use Illuminate\Console\Command;

class ClearJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all jobs';

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
        $this->info('Clearing: jobs');
        Job::truncate();
    }
}
