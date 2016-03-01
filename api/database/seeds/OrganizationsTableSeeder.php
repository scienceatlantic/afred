<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Organization;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Delete existing entries.
        DB::table('organizations')->delete();
        
        $organizations = [
            [
                'name'      => 'N/A',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Acadia University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Cape Breton University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Crandall University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Dalhousie University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Dalhousie University, Faculty of Agriculture',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Memorial University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Memorial University, Grenfell Campus',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Mount Allison University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Mount Saint Vincent University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Saint Mary\'s University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'St. Francis Xavier University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'St. Thomas University',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'Université de Moncton', //FIX THE ACCENTED E!
                'isHidden'  => false,
                'dateAdded' => $now
            ], 
            [
                'name'      => 'University of New Brunswick, Fredericton',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'University of New Brunswick, Saint John',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
            [
                'name'      => 'University of Prince Edward Island',
                'isHidden'  => false,
                'dateAdded' => $now
            ],
        ];
        
        foreach($organizations as $organization) {
            Organization::create($organization);
        }
    }
}
