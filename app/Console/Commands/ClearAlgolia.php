<?php

namespace App\Console\Commands;

use App\Algolia;
use Illuminate\Console\Command;

class ClearAlgolia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears Algolia indices';

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
        $indices = explode(',', env('ALGOLIA_DEV_INDICES', ''));

        $client = Algolia::getClient();

        foreach($indices as $index) {
            $client->initIndex($index)->clearIndex();
        }
    }
}
