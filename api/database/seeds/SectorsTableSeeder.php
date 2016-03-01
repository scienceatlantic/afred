<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Sector;

class SectorsTableSeeder extends Seeder
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
        DB::table('sectors')->delete();
        
        $sectors = [
            [
                'name'      => 'Aerospace and satellites',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Agriculture, animal science and food',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Arts and cultural industries',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Automotive',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Chemical industries',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Clean technology',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Construction (including building, civil engineering, specialty trades)',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Consumer durables',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Consumer non-durables',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Defense and security industries',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Education',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Energy (renewable and fossil)',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Environmental technologies and related services',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Financial services and insurance',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Fisheries and aquaculture',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Forestry and forest-based industries',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Health care and social services',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Information and communication technologies and media',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Life sciences, pharmaceuticals and medical equipment',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Management and business related services',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Manufacturing and processing',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Mining, minerals and metals',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Ocean industries',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Policy and governance',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Professional and technical services (including legal services, architecture, engineering)',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Tourism and hospitality',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Transportation',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Utilities',
                'dateAdded' => $now
            ]
        ];
        
        foreach($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
