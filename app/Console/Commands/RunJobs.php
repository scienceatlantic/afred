<?php

namespace App\Console\Commands;

use App\Jobs;
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
    protected $description = '';

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
        $numJobs = Jobs::count();
        for ($i = 0; $i < $numJobs; $i++) {
            $this->call('queue:work', [
                '--once',
                '--retries=10'
            ]);
        }
    }
}
