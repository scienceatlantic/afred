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
                'first_name'    => 'Peggy',
                'last_name'     => 'Crawford',
                'email'         => 'peggy.crawford',
                'telephone'     => '9025851762',
                'position'      => 'Research and Innovation Coordinator'
            ],
            'Cape Breton University' => [
                'first_name'    => 'Sarah',
                'last_name'     => 'Conrod',
                'email'         => 'sarah_conrod@cbu.ca',
                'telephone'     => '9025631842',
                'position'      => 'Industry Liaison Officer'
            ],
            'Crandall Universtiy' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Dalhousie University' => [
                'first_name'    => 'Kevin',
                'last_name'     => 'Dunn',
                'email'         => 'kevin.dunn@dal.ca',
                'telephone'     => '9024941648',
                'position'      => 'Director'
            ],
            'Dalhouse University, Faculty of Agriculture' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Memorial University' => [
                'first_name'    => 'Marc',
                'last_name'     => 'Kielley',
                'email'         => 'mkielley@mun.ca',
                'telephone'     => '7098642095',
                'position'      => 'Manager of Industry Engagement'
            ],
            'Memorial University, Grenfell Campus' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Mount Allison University' => [
                'first_name'    => 'Cassidy',
                'last_name'     => 'Weisbord',
                'email'         => 'cweisbord@mta.ca',
                'telephone'     => '5068663469',
                'position'      => 'Industry Liaison Officer'
            ],
            'Mount Saint Vincent University' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Saint Mary\'s University' => [
                'first_name'    => 'Kevin',
                'last_name'     => 'Buchan',
                'email'         => 'kevin.buchan@smu.ca',
                'telephone'     => '9024916297',
                'position'      => 'Director'
            ],
            'St. Francis Xavier University' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'St. Thomas University' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'Université de Moncton' => [
                'first_name'    => 'Cassidy',
                'last_name'     => 'Weisbord',
                'email'         => 'cassidy.weisbord@umonton.ca',
                'telephone'     => '5068584307',
                'position'      => 'Innovation Officer'
            ],
            'University of New Brunswick, Fredericton' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'University of New Brunswick, Saint John' => [
                'first_name'    => '',
                'last_name'     => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ],
            'University of Prince Edward Island1' => [
                'first_name'    => 'Shelley',
                'last_name'     => 'King',
                'email'         => 'srking@upei.ca',
                'telephone'     => '9025666095',
                'position'      => 'Chief Executive Officer'
            ]
        ];
        
        foreach ($ilos as $institutionName => $ilo) {
            // Ignore ILO if it's empty. We're only checking the 'first_name'
            // field.
            if ($ilo['first_name']) {
                // Get the institution's primary key.
                $institution = DB::table('institutions')->
                    select('id')->
                    where('name', $institutionName)->first();
                
                // Insert the ILO's data only if the institution was found.
                if ($institution && property_exists($institution, 'id')) {
                    $ilo['institution_id'] = $institution->id;
                    DB::table('ilos')->insert($ilo);  
                }
            }
        }
    }
}
