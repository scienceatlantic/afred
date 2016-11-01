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
        // Contains real data.
        $this->call(ProvincesTableSeeder::class);
        $this->call(OrganizationsTableSeeder::class);
        $this->call(IlosTableSeeder::class);
        $this->call(DisciplinesTableSeeder::class);
        $this->call(SectorsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        
        // Contains dummy data.
        if (env('APP_ENV') == 'local') {
            $this->call(DummyUsersTableSeeder::class);
        }
        
        // Import data from AFRED v1.0.
        //$this->call(ImportAfredV1DataSeeder::class);
    }
}
