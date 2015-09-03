<?php
class InstitutionTableSeeder extends Seeder
{
    public function run()
    {
        //Information collected from: http://springboardatlantic.ca/about-us/members/
        $institutions = array(
            array(
                'institution'   => 'Acadia University',
                'firstName'     => 'Peggy',
                'lastName'      => 'Crawford',
                'email'         => 'peggy.crawford',
                'telephone'     => '9025851762',
                'position'      => 'Research and Innovation Coordinator'
            ),
            array(
                'institution'   => 'Cape Breton University',
                'firstName'     => 'Sarah',
                'lastName'      => 'Conrod',
                'email'         => 'sarah_conrod@cbu.ca',
                'telephone'     => '9025631842',
                'position'      => 'Industry Liaison Officer'
            ),
            array(
                'institution'   => 'Crandall University',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'Dalhouse University',
                'firstName'     => 'Kevin',
                'lastName'      => 'Dunn',
                'email'         => 'kevin.dunn@dal.ca',
                'telephone'     => '9024941648',
                'position'      => 'Director'
            ),
            array(
                'institution'   => 'Dalhouse University, Faculty of Agriculture',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'Memorial University',
                'firstName'     => 'Marc',
                'lastName'      => 'Kielley',
                'email'         => 'mkielley@mun.ca',
                'telephone'     => '7098642095',
                'position'      => 'Manager of Industry Engagement'
            ),
            array(
                'institution'   => 'Memorial University, Grenfell Campus',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'Mount Allison University',
                'firstName'     => 'Cassidy',
                'lastName'      => 'Weisbord',
                'email'         => 'cweisbord@mta.ca',
                'telephone'     => '5068663469',
                'position'      => 'Industry Liaison Officer'
            ),
            array(
                'institution'   => 'Mount Saint Vincent University',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'Saint Mary\'s University',
                'firstName'     => 'Kevin',
                'lastName'      => 'Buchan',
                'email'         => 'kevin.buchan@smu.ca',
                'telephone'     => '9024916297',
                'position'      => 'Director'
            ),
            array(
                'institution'   => 'St. Francis Xavier University',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'St. Thomas University',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'Université de Moncton',
                'firstName'     => 'Cassidy',
                'lastName'      => 'Weisbord',
                'email'         => 'cassidy.weisbord@umonton.ca',
                'telephone'     => '5068584307',
                'position'      => 'Innovation Officer'
            ),
            array(
                'institution'   => 'University of New Brunswick, Fredericton',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'University of New Brunswick, Saint John',
                'firstName'     => '',
                'lastName'      => '',
                'email'         => '',
                'telephone'     => '',
                'position'      => ''
            ),
            array(
                'institution'   => 'University of Prince Edward Island',
                'firstName'     => 'Shelley',
                'lastName'      => 'King',
                'email'         => 'srking@upei.ca',
                'telephone'     => '9025666095',
                'position'      => 'Chief Executive Officer'
            )
        );
        
        DB::table('institutions')->delete();
        DB::table('ilo_contacts')->delete();
                
        foreach($institutions as $i) {
            $institution = new Institution();
            $institution->name = $i['institution'];
            $institution->save();
            
            if ($i['firstName']) {
                $iloContact = new IloContact();
                $iloContact->institutionId = $institution->id;
                $iloContact->firstName = $i['firstName'];
                $iloContact->lastName = $i['lastName'];
                $iloContact->email = $i['email'];
                $iloContact->telephone = $i['telephone'];
                $iloContact->position = $i['position'];
                $iloContact->save();
            }            
        }
    }
}