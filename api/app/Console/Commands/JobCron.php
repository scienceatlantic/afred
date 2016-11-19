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
    protected $description = 'Runs `queue:work --once` repeatedly';

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

        $s = Setting::lookup([
            'cronJobNumCycles',
            'cronJobSleepDuration'
        ]); 
        $numCycles = $this->filterNumCycles($s['cronJobNumCycles']);
        $sleepDur = $this->filterSleepDuration($s['cronJobSleepDuration']);
        
        // Create raw command string.
        // Calling `$this->call('queue:work', ['--once']);` does not seem to 
        // work (i.e. argument `--once` is ignored and queue is run in daemon
        // mode).
        $command = ' ' . __DIR__ . '/../../../artisan queue:work --once';

        for ($cycle = 1; $cycle <= $numCycles; $cycle++) {   
            $numJobs = DB::table('jobs')->count();
            $numJobsExecuted = 0;
            for ($job = 1; $job <= $numJobs; $job++) {
                $id = DB::table('jobs')->first()->id;

                exec(PHP_BINARY . $command);
                
                if (!DB::table('jobs')->where('id', $id)->first()) {
                    $numJobsExecuted++;
                }
            }
            if ($numJobs === $numJobsExecuted) {
                Log::info('Executed ' . $numJobs . ' job(s).');
            } else {
                Log::warning('Found ' . $numJobs . ' job(s) but only ' 
                    . $numJobsExecuted . ' job(s) successfully executed.');
            }
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
        } else if ($num > 6) {
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
        } else if ($num > 150) {
            Log::warning('Sleep duration more than 150.', [
                'cronJobSleepDuration' => $num
            ]);
            return 150;
        } else {
            return $num;
        }
    }
}
