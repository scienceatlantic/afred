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
        if (!$devIndexPrefix = env('ALGOLIA_DEV_INDEX_PREFIX', null)) {
            $this->error('\'ALGOLIA_DEV_INDEX_PREFIX is empty\'. Aborting!');
            return;
        }

        $client = Algolia::getClient();
        $indices = $client->listIndexes()['items'];

        foreach($indices as $index) {
            $indexPrefix = substr($index['name'], 0, strlen($devIndexPrefix));
            
            if ($indexPrefix === $devIndexPrefix) {
                $this->info('Clearing: ' . $index['name'] . ' index');
                $client->initIndex($index['name'])->clearIndex();
            }
        }
    }
}
