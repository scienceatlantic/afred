<?php

use App\LabelledValue;

class IlosTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ilos = [
            [
                'labelled_value_id' => self::getId('Acadia University'),
                'first_name'        => 'Leigh',
                'last_name'         => 'Huestis',
                'email'             => 'leigh.huestis@acadiau.ca',
                'telephone'         => '9025851425',
                'extension'         => '',
                'position'          => 'Director',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Cape Breton University'),
                'first_name'        => 'Sarah',
                'last_name'         => 'Conrod',
                'email'             => 'sarah_conrod@cbu.ca',
                'telephone'         => '9025631842',
                'extension'         => '',
                'position'          => 'Industry Liaison Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Collège communautaire du Nouveau-Brunswick'),
                'first_name'        => 'Alain',
                'last_name'         => 'Doucet',
                'email'             => 'alain.doucet@gnb.ca',
                'telephone'         => '5065472190',
                'extension'         => '',
                'position'          => 'Industrial Liaison Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('College of the North Atlantic'),
                'first_name'        => 'Kay',
                'last_name'         => 'Graham',
                'email'             => 'kay.graham@cna.nl.ca',
                'telephone'         => '7098915658',
                'extension'         => '',
                'position'          => 'Innovation Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Dalhousie University'),
                'first_name'        => 'Margaret',
                'last_name'         => 'Palmeter',
                'email'             => 'margaret.palmeter@dal.ca',
                'telephone'         => '9024941693',
                'extension'         => '',
                'position'          => 'Manager',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Dalhousie University, Faculty of Agriculture'),
                'first_name'        => 'Margaret',
                'last_name'         => 'Palmeter',
                'email'             => 'margaret.palmeter@dal.ca',
                'telephone'         => '9024941693',
                'extension'         => '',
                'position'          => 'Manager',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Holland College'),
                'first_name'        => 'Shawn',
                'last_name'         => 'MacDougall',
                'email'             => 'samacdougall@hollandcollege.com',
                'telephone'         => '9025669361',
                'extension'         => '',
                'position'          => 'Research Development Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Memorial University'),
                'first_name'        => 'Matt',
                'last_name'         => 'Grimes',
                'email'             => 'mgrimes@mun.ca',
                'telephone'         => '7098643048',
                'extension'         => '',
                'position'          => 'Technology Commercialization Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Memorial University, Grenfell Campus'),
                'first_name'        => 'Matt',
                'last_name'         => 'Grimes',
                'email'             => 'mgrimes@mun.ca',
                'telephone'         => '7098643048',
                'extension'         => '',
                'position'          => 'Technology Commercialization Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Mount Allison University'),
                'first_name'        => 'David',
                'last_name'         => 'Bruce',
                'email'             => 'dwbruce@mta.ca',
                'telephone'         => '5063642618',
                'extension'         => '',
                'position'          => 'Director',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Mount Saint Vincent University'),
                'first_name'        => 'Kevin',
                'last_name'         => 'Buchan',
                'email'             => 'kevin.buchan@smu.ca',
                'telephone'         => '9024916297',
                'extension'         => '',
                'position'          => 'Director',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('New Brunswick Community College'),
                'first_name'        => 'Jennifer',
                'last_name'         => 'McCarthy',
                'email'             => 'jennifer.mccarthy@nbcc.ca',
                'telephone'         => '5068562917',
                'extension'         => '',
                'position'          => 'Applied Research Development Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Nova Scotia Community College'),
                'first_name'        => 'Beth',
                'last_name'         => 'McCormack',
                'email'             => 'beth.mccormack@nscc.ca',
                'telephone'         => '9024913203',
                'extension'         => '',
                'position'          => 'Industry Liaison Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('NSCAD University'),
                'first_name'        => 'Kevin',
                'last_name'         => 'Buchan',
                'email'             => 'kevin.buchan@smu.ca',
                'telephone'         => '9024916297',
                'extension'         => '',
                'position'          => 'kevin.buchan@smu.ca',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Saint Mary\'s University'),
                'first_name'        => 'Kevin',
                'last_name'         => 'Buchan',
                'email'             => 'kevin.buchan@smu.ca',
                'telephone'         => '9024916297',
                'extension'         => '',
                'position'          => 'Director',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('St. Francis Xavier University'),
                'first_name'        => 'Andrew',
                'last_name'         => 'Kendall',
                'email'             => 'akendall@stfx.ca',
                'telephone'         => '9028673660',
                'extension'         => '',
                'position'          => 'Manager',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('St. Thomas University'),
                'first_name'        => 'Danielle',
                'last_name'         => 'Connell',
                'email'             => 'dconnell@stu.ca',
                'telephone'         => '5064520647',
                'extension'         => '',
                'position'          => 'Faculty Research Office Assistant',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('Université de Moncton'),
                'first_name'        => 'Charles',
                'last_name'         => 'Thibodeau',
                'email'             => 'charles.thibodeau@umoncton.ca',
                'telephone'         => '5068584454',
                'extension'         => '',
                'position'          => 'Gestionnaire – Bureau de soutien à l’innovation',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('University of New Brunswick, Fredericton'),
                'first_name'        => 'Alison',
                'last_name'         => 'MacNevin',
                'email'             => 'alison.macnevin@unb.ca',
                'telephone'         => '5064473296',
                'extension'         => '',
                'position'          => 'Knowledge Transfer Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('University of New Brunswick, Saint John'),
                'first_name'        => 'Matthew',
                'last_name'         => 'Douglass',
                'email'             => 'm.douglass@unb.ca',
                'telephone'         => '5064473296',
                'extension'         => '',
                'position'          => 'Knowledge Transfer Officer',
                'website'           => ''
            ],
            [
                'labelled_value_id' => self::getId('University of Prince Edward Island'),
                'first_name'        => 'Kimberley',
                'last_name'         => 'Johnstone',
                'email'             => 'kjohnstone@upei.ca',
                'telephone'         => '9026205115',
                'extension'         => '',
                'position'          => 'Technology Transfer & Industry Liaison Officer',
                'website'           => ''
            ]
        ];

        self::saveModels('Ilo', $ilos);
    }

    public static function getId($label)
    {
        return LabelledValue::where('label', $label)->first()->id;
    }
}
