<?php

namespace App\Console\Commands;

// Laravel.
use Illuminate\Console\Command;

// Misc.
use Artisan;
use DB;
use Log;

class JobCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs `queue:work` over and over again';

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
        $numCycles = env('_JOB_CRON_NUM_CYCLES', 6);
        $sleepDuration = env('_JOB_CRON_SLEEP_DURATION', 150);
        
        for ($cycle = 1; $cycle <= $numCycles; $cycle++) {   
            $numJobs = DB::table('jobs')->count();
            for ($job = 1; $job <= $numJobs; $job++) {
                Artisan::call('queue:work');
            }
            Log::debug('queue:work');
            sleep($sleepDuration);
        }
    }
}
