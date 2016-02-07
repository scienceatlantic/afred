<?php

// Laravel.
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
        $this->call(ProvincesTableSeeder::class);
        $this->call(OrganizationsTableSeeder::class);
        $this->call(IlosTableSeeder::class);
        $this->call(DisciplinesTableSeeder::class);
        $this->call(SectorsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        
        // Contains dummy data.
        if (env('APP_ENV') == 'local') {
            $this->call(DummyUsersTableSeeder::class);
            $this->call(DummyFacilitiesTableSeeder::class);            
        }

        Model::reguard();
    }
}
