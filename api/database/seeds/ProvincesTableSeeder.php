<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Province;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Delete existing entries
        DB::table('provinces')->delete();
        
        $provinces = [[
            'name'        => 'N/A',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Atlantic Region',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now                      
        ], [
            'name'        => 'Pacific Region',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now                      
        ], [
            'name'        => 'Prairies Region',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now                      
        ], [
            'name'        => 'Alberta',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'British Columbia',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Manitoba',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'New Brunswick',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Newfoundland and Labrador',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Northwest Territories',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Nova Scotia',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Nunavut',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Ontario',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Prince Edward Island',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Quebec',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Saskatchewan',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Yukon',
            'isHidden'    => true,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
        foreach($provinces as $province) {
            Province::create($province);   
        }
    }
}
