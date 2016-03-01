<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Organization;
use App\Ilo;

class IlosTableSeeder extends Seeder
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
        DB::table('ilos')->delete();
        
        $ilos = [
            'Acadia University' => [
                'firstName' => 'Peggy',
                'lastName'  => 'Crawford',
                'email'     => 'peggy.crawford@acadiau.ca',
                'telephone' => '9025851762',
                'position'  => 'Research and Innovation Coordinator',
                'dateAdded' => $now
            ],
            'Cape Breton University' => [
                'firstName' => 'Sarah',
                'lastName'  => 'Conrod',
                'email'     => 'sarah_conrod@cbu.ca',
                'telephone' => '9025631842',
                'position'  => 'Industry Liaison Officer',
                'dateAdded' => $now
            ],
            'Crandall Universtiy' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'Dalhousie University' => [
                'firstName' => 'Kevin',
                'lastName'  => 'Dunn',
                'email'     => 'kevin.dunn@dal.ca',
                'telephone' => '9024941648',
                'position'  => 'Director',
                'dateAdded' => $now
            ],
            'Dalhouse University, Faculty of Agriculture' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'Memorial University' => [
                'firstName' => 'Marc',
                'lastName'  => 'Kielley',
                'email'     => 'mkielley@mun.ca',
                'telephone' => '7098642095',
                'position'  => 'Manager of Industry Engagement',
                'dateAdded' => $now
            ],
            'Memorial University, Grenfell Campus' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'Mount Allison University' => [
                'firstName' => 'Cassidy',
                'lastName'  => 'Weisbord',
                'email'     => 'cweisbord@mta.ca',
                'telephone' => '5068663469',
                'position'  => 'Industry Liaison Officer',
                'dateAdded' => $now
            ],
            'Mount Saint Vincent University' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'Saint Mary\'s University' => [
                'firstName' => 'Kevin',
                'lastName'  => 'Buchan',
                'email'     => 'kevin.buchan@smu.ca',
                'telephone' => '9024916297',
                'position'  => 'Director',
                'dateAdded' => $now
            ],
            'St. Francis Xavier University' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'St. Thomas University' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'Université de Moncton' => [
                'firstName' => 'Cassidy',
                'lastName'  => 'Weisbord',
                'email'     => 'cassidy.weisbord@umonton.ca',
                'telephone' => '5068584307',
                'position'  => 'Innovation Officer',
                'dateAdded' => $now
            ],
            'University of New Brunswick, Fredericton' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'University of New Brunswick, Saint John' => [
                'firstName' => '',
                'lastName'  => '',
                'email'     => '',
                'telephone' => '',
                'position'  => '',
                'dateAdded' => $now
            ],
            'University of Prince Edward Island' => [
                'firstName' => 'Shelley',
                'lastName'  => 'King',
                'email'     => 'srking@upei.ca',
                'telephone' => '9025666095',
                'position'  => 'Chief Executive Officer',
                'dateAdded' => $now
            ]
        ];
        
        foreach ($ilos as $orgName => $ilo) {
            // Ignore ILO if it's empty. We're only checking the 'firstName'
            // field to verify this.
            if ($ilo['firstName']) {
                // Get the organization's primary key.
                $org = Organization::select('id')->where('name', $orgName)
                    ->first();
                
                // Insert the ILO's data only if the organization was found.
                if ($org) {
                    $ilo['organizationId'] = $org->id;
                    Ilo::create($ilo);  
                }
            }
        }
    }
}
