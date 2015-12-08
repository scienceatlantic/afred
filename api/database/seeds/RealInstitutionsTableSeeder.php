<?php

use Illuminate\Database\Seeder;

class RealInstitutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries.
        DB::table('institutions')->delete();
        
        DB::table('institutions')->insert([
            [
                'name'      => 'N/A',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Acadia University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Cape Breton University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Crandall University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Dalhousie University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Dalhousie University, Faculty of Agriculture',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Memorial University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Memorial University, Grenfell Campus',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Mount Allison University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Mount Saint Vincent University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Saint Mary\'s University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'St. Francis Xavier University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'St. Thomas University',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'Universit� de Moncton', //FIX THE ACCENTED E!
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ], 
            [
                'name'      => 'University of New Brunswick, Fredericton',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'University of New Brunswick, Saint John',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
            [
                'name'      => 'University of Prince Edward Island',
                'isHidden'  => false,
                'dateAdded' => Carbon::now()
            ],
        ]);
    }
}
