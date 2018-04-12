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
        $repeat = 4;

        // Repeat the command (some events generate their own events and
        // running this again allows us to execute all of those right now
        // instead of waiting for the next time this is called via cron).
        for ($repeatCount = 0; $repeatCount < $repeat; $repeatCount++) {
            $startTime = time();
            $numJobs = Job::count();
    
            // Calling `$this->call('queue:work', ['--once']);` does not seem to 
            // work (i.e. argument `--once` is ignored and queue is run in
            // daemon mode).
            $command = ' ' . __DIR__ . '/../../../artisan queue:work --once';        
    
            for ($i = 0; $i < $numJobs; $i++) {
                // Timeout after 50 seconds (since we're running this command
                // every minute)
                if (time() - $startTime > 50) {
                    exit();
                }   
    
                exec(PHP_BINARY . $command);
            }
        }
    }
}
