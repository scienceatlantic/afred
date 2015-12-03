<?php

use Illuminate\Database\Seeder;

class RealIlosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries
        DB::table('ilos')->delete();
        
        $ilos = [
            'Acadia University' => [
                'firstName'    => 'Peggy',
                'lastName'     => 'Crawford',
                'email'         => 'peggy.crawford',
                'telephone'     => '9025851762',
                'position'      => 'Research and Innovation Coordinator'
            ],
            'Cape Breton University' => [
                'firstName'    => 'Sarah',
                'lastName'     => 'Conrod',
                'email'         => 'sarah_conrod@cbu.ca',
                'telephone'     => '9025631842',
                'position'      => 'Industry Liaison Officer'
            ],
            'Crandall Universtiy' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Dalhousie University' => [
                'firstName'    => 'Kevin',
                'lastName'     => 'Dunn',
                'email'         => 'kevin.dunn@dal.ca',
                'telephone'     => '9024941648',
                'position'      => 'Director'
            ],
            'Dalhouse University, Faculty of Agriculture' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Memorial University' => [
                'firstName'    => 'Marc',
                'lastName'     => 'Kielley',
                'email'         => 'mkielley@mun.ca',
                'telephone'     => '7098642095',
                'position'      => 'Manager of Industry Engagement'
            ],
            'Memorial University, Grenfell Campus' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Mount Allison University' => [
                'firstName'    => 'Cassidy',
                'lastName'     => 'Weisbord',
                'email'         => 'cweisbord@mta.ca',
                'telephone'     => '5068663469',
                'position'      => 'Industry Liaison Officer'
            ],
            'Mount Saint Vincent University' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Saint Mary\'s University' => [
                'firstName'    => 'Kevin',
                'lastName'     => 'Buchan',
                'email'         => 'kevin.buchan@smu.ca',
                'telephone'     => '9024916297',
                'position'      => 'Director'
            ],
            'St. Francis Xavier University' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'St. Thomas University' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Université de Moncton' => [
                'firstName'    => 'Cassidy',
                'lastName'     => 'Weisbord',
                'email'         => 'cassidy.weisbord@umonton.ca',
                'telephone'     => '5068584307',
                'position'      => 'Innovation Officer'
            ],
            'University of New Brunswick, Fredericton' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'University of New Brunswick, Saint John' => [
                'firstName'    => '',
                'lastName'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'University of Prince Edward Island1' => [
                'firstName'    => 'Shelley',
                'lastName'     => 'King',
                'email'         => 'srking@upei.ca',
                'telephone'     => '9025666095',
                'position'      => 'Chief Executive Officer'
            ]
        ];
        
        foreach ($ilos as $institutionName => $ilo) {
            // Ignore ILO if it's empty. We're only checking the 'firstName'
            // field.
            if ($ilo['firstName']) {
                // Get the institution's primary key.
                $institution = DB::table('institutions')->
                    select('id')->
                    where('name', $institutionName)->first();
                
                // Insert the ILO's data only if the institution was found.
                if ($institution && property_exists($institution, 'id')) {
                    $ilo['institutionId'] = $institution->id;
                    DB::table('ilos')->insert($ilo);  
                }
            }
        }
    }
}
