<?php

namespace App\Console\Commands;

use App\Job;
use Illuminate\Console\Command;

class RunJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls `queue:work --once` based on the number of '
                           . 'jobs that are currently in the jobs table. Will ' 
                           . 'timeout after a certain period.';

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
        $startTime = time();
        $numJobs = Job::count();

        for ($i = 0; $i < $numJobs; $i++) {
            // Timeout after 2.5 minutes
            if (time() - $startTime > 150) {
                exit();
            }

            $this->call('queue:work', ['--once']);
        }
    }
}
