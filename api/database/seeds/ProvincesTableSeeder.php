<?php

use Illuminate\Database\Seeder;
use App\Province;
use Carbon\Carbon;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries
        DB::table('provinces')->delete();
        
        $provinces = [
            [
                'name'      => 'Alberta',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'British Columbia',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Manitoba',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'New Brunswick',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Newfoundland and Labrador',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Northwest Territories',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Nova Scotia',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Nunavut',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Ontario',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Prince Edward Island',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Quebec',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Saskatchewan',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Yukon',
                'isHidden'  => true,
                'dateAdded' => Carbon::now()
            ]
        ];
        
        foreach($provinces as $province) {
            Province::create($province);   
        }
    }
}
