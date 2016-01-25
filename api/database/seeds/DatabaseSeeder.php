<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        // Contains real data.
        $this->call(RealProvincesTableSeeder::class);
        $this->call(RealOrganizationsTableSeeder::class);
        $this->call(RealIlosTableSeeder::class);
        $this->call(RealSettingsTableSeeder::class);
        
        // Contains dummy data.
        //$this->call(DummyFacilitiesTableSeeder::class);
        $this->call(DummySystemUsersTableSeeder::class);
        
        Model::reguard();
    }
}
