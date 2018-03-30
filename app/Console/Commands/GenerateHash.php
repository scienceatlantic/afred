<?php

namespace App\Console\Commands;

use Hash;
use Illuminate\Console\Command;

class GenerateHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:generate '
                         . '{string : String to hash or encode} '
                         . '{method=bcrypt : Method to use (bcrypt or base64}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a hash or an encoding of the string '
                           . 'provided';

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
        $input = $this->argument('string');
        $method = $this->argument('method');
        
        switch ($method) {
            case 'bcrypt':
                $this->info('Hashing (bcrypt): ' . $input);
                $this->info(Hash::make($input));
                break;
            case 'base64':
                $this->info('Encoding (base64): ' . $input);
                $this->info(base64_encode($input));
                break;
            default:
                $msg = 'Invalid method provided. Valid methods are \'bcrypt\' '
                     . 'or \'base64\'';
                $this->error($msg);
        }
    }
}
