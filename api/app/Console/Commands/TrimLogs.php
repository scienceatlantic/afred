<?php

namespace App\Console\Commands;

// Laravel.
use Illuminate\Console\Command;

// Misc.
use Log;

class TrimLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:trim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates the log file and creates historical
                              copies';

    /**
     * The maximum number of log (historical) files to keep (must be <= 50).
     *
     * @var int
     */
    private $maxNumLogs = 5;

    /**
     * The maximum size (in MiB) of the log file before it is truncated.
     *
     * @var float
     */
    private $maxLogSize = 10;    

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
     * Truncates the log file if it is larger than `TrimLogs::$maxLogSize` and 
     * keeps a historical maximum of `TrimLogs::$maxNumLogs` files.
     *
     * @uses TrimLogs::renameLog() to make a copy of and truncate the log file.
     * @uses TrimLogs::clearLogs() to keep the number of (historical) log files 
     *     equal to or below `$maxNumLogs`.
     *
     * @return null
     */
    public function handle()
    {
        Log::info('Console: `trim:logs` called.');

        $mib = 1048576.0; // MiB.
        $log = storage_path() . '/logs/laravel.log';
        if (file_exists($log)) {
            if ((filesize($log) / $mib) > $this->maxLogSize) {
                $this->renameLog();
                $this->clearLogs();
                Log::info('Log file successfully trimmed.');
            }
        }
    }

    /**
     * Truncates the log file.
     *
     * A copy of the log file will be created in this format:
     * 'laravel.#.log' (# - starts from 0)
     */
    private function renameLog()
    {
        $pfx = storage_path() . '/logs/laravel.';
        $sfx = '.log';
        $i = 0;
        while (true) {
            if (!file_exists($pfx . $i . $sfx)) {
                // Copy the file instead of renaming it (i.e. lock issues).
                copy($pfx . substr($sfx, 1), $pfx . $i . $sfx);
                // Safely truncate the file (avoiding file lock issues).
                fclose(fopen($pfx . substr($sfx, 1), 'w')); 
                return;
            }
            $i++;

            // To prevent (quite unlikely that this would happen but just 
            // in case) infite loops.
            if ($i >= 50) {
                Log::error('Number of log files exceeded 50, terminating.');
                abort(500);
            }
        }
    }

    /**
     * Ensures that there are only `TrimLogs::$maxNumLogs` number of historical
     * log files.
     */
    private function clearLogs()
    {
        $pfx = storage_path() . '/logs/laravel.';
        $sfx = '.log';
        if (file_exists($pfx . $this->maxNumLogs . $sfx)) {
            for ($i = 0; $i < $this->maxNumLogs; $i++) {
                rename($pfx . ($i + 1) . $sfx, $pfx . $i . $sfx);
            }           
        }
    }
}
