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
        
        $provinces = [
            [
                'name'      => 'N/A',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Alberta',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'British Columbia',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Manitoba',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'New Brunswick',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Newfoundland and Labrador',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Northwest Territories',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Nova Scotia',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Nunavut',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Ontario',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Prince Edward Island',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Quebec',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Saskatchewan',
                'isHidden'  => true,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Yukon',
                'isHidden'  => true,
                'dateAdded' => $now
            ]
        ];
        
        foreach($provinces as $province) {
            Province::create($province);   
        }
    }
}
