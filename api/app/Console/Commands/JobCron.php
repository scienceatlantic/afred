<?php

namespace App\Console\Commands;

// Laravel.
use Illuminate\Console\Command;

// Misc.
use Artisan;
use DB;
use Exception;
use Log;

// Models.
use App\Setting;

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
        Log::info('Command: `cron:job` called.');

        $db = Setting::lookup([
            'cronJobNumCycles',
            'cronJobSleepDuration'
        ]); 
        $numCycles = $this->filterNumCycles($db['cronJobNumCycles']);
        $sleepDur = $this->filterSleepDuration($db['cronJobSleepDuration']);
        
        for ($cycle = 1; $cycle <= $numCycles; $cycle++) {   
            $numJobs = DB::table('jobs')->count();
            for ($job = 1; $job <= $numJobs; $job++) {
                Artisan::call('queue:work');
            }
            Log::info('Processed ' . $numJobs . ' job(s).');
            sleep($sleepDur);
        }
    }

    private static function filterNumCycles($num)
    {
        if ($num <= 0) {
            Log::warning('Number of cycles less than 0.', [
                'cronJobNumCycles' => $num
            ]);
            return 1;
        } else if ($num >= 6) {
            Log::warning('Number of cycles more than 6.', [
                'cronJobNumCycles' => $num
            ]);
            return 6;
        } else {
            return $num;
        }
    }

    private static function filterSleepDuration($num)
    {
        if ($num <= 0) {
            Log::warning('Sleep duration less than 0.', [
                'cronJobSleepDuration' => $num
            ]);
            return 1;
        } else if ($num >= 150) {
            Log::warning('Sleep duration more than 150.', [
                'cronJobSleepDuration' => $num
            ]);
            return 150;
        } else {
            return $num;
        }
    }
}
