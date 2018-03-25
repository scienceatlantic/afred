<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ClearWordpress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordpress:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all WordPress resources';

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
        if (!$databases = explode(',', env('DB_DEV_WP_DATABASES', null))) {
            $this->error('\'DB_DEV_WP_DATABASES is empty\'. Aborting!');
            return;
        }
        

        foreach($databases as $database) {
            $this->info('Clearing: ' . $database . ' database');

            // Set database connection
            config(['database.connections.mysql.database' => $database]);

            // Clear connection cache before running command below
            DB::purge('mysql');
            
            // Remove all plugin related posts
            DB::table('wp_posts')
                ->where('post_type', 'afredwp_resource')
                ->delete();
        }

        // Clear connection cache again after command is complete.
        DB::purge('mysql');
    }
}
