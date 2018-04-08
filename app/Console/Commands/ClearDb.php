<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Schema;

class ClearDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the database. Useful for when a migration '
                           . 'command fails and we need to clear the database '
                           . 'force.';

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $property = 'Tables_in_' . DB::getDatabaseName();

        foreach(DB::select('SHOW TABLES') as $table) {
            $this->info('Dropping: ' . $table->$property);
            Schema::drop($table->$property);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
