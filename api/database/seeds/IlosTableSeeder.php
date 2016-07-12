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
        
        $ilos = ['Acadia University' => [
            'firstName'   => 'Leigh',
            'lastName'    => 'Huestis',
            'email'       => 'leigh.huestis@acadiau.ca',
            'telephone'   => '9025851425',
            'position'    => 'Director',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Cape Breton University' => [
            'firstName'   => 'Sarah',
            'lastName'    => 'Conrod',
            'email'       => 'sarah_conrod@cbu.ca',
            'telephone'   => '9025631842',
            'position'    => 'Industry Liaison Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Crandall University' => [
            'firstName'   => '',
            'lastName'    => '',
            'email'       => '',
            'telephone'   => '',
            'position'    => '',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Collège communautaire du Nouveau-Brunswick' => [
            'firstName'   => 'Joey',
            'lastName'    => 'Volpe',
            'email'       => 'joey.volpe@ccnb.ca',
            'telephone'   => '5067352555',
            'position'    => 'Industry Liaison Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'College of the North Atlantic' => [
            'firstName'   => 'Kay',
            'lastName'    => 'Graham',
            'email'       => 'kay.graham@cna.nl.ca',
            'telephone'   => '7098915658',
            'position'    => 'Innovation Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Dalhousie University' => [
            'firstName'   => 'Margaret',
            'lastName'    => 'Palmeter',
            'email'       => 'margaret.palmeter@dal.ca',
            'telephone'   => '9024941693',
            'position'    => 'Manager',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Dalhouse University, Faculty of Agriculture' => [
            'firstName'   => 'David',
            'lastName'    => 'Fullerton',
            'email'       => 'david.fullerton@dal.ca',
            'telephone'   => '9028996683',
            'position'    => 'Industry Liaison Officer - Faculty of Agriculture',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Holland College' => [
            'firstName'   => 'Shawn',
            'lastName'    => 'MacDougall',
            'email'       => 'samacdougall@hollandcollege.com',
            'telephone'   => '9025669361',
            'position'    => 'Research Development Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Memorial University' => [
            'firstName'   => 'Matt',
            'lastName'    => 'Grimes',
            'email'       => 'mgrimes@mun.ca',
            'telephone'   => '7098643048',
            'position'    => 'Technology Commercialization Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Memorial University, Grenfell Campus' => [
            'firstName'   => 'Matt',
            'lastName'    => 'Grimes',
            'email'       => 'mgrimes@mun.ca',
            'telephone'   => '7098643048',
            'position'    => 'Technology Commercialization Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Mount Allison University' => [
            'firstName'   => 'Cassidy',
            'lastName'    => 'Weisbord',
            'email'       => 'cweisbord@mta.ca',
            'telephone'   => '5068663469',
            'position'    => 'Industry Liaison Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Mount Saint Vincent University' => [
            'firstName'   => 'Kevin',
            'lastName'    => 'Buchan',
            'email'       => 'kevin.buchan@smu.ca',
            'telephone'   => '9024916297',
            'position'    => 'Director',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'New Brunswick Community College' => [
            'firstName'   => 'Jennifer',
            'lastName'    => 'McCarthy',
            'email'       => 'jennifer.mccarthy@nbcc.ca',
            'telephone'   => '5068562917',
            'position'    => 'Applied Research Development Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Nova Scotia Community College' => [
            'firstName'   => 'Beth',
            'lastName'    => 'McCormack',
            'email'       => 'beth.mccormack@nscc.ca',
            'telephone'   => '9024913203',
            'position'    => 'Industry Liaison Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'NSCAD University' => [
            'firstName'   => 'Kevin',
            'lastName'    => 'Buchan',
            'email'       => 'kevin.buchan@smu.ca',
            'telephone'   => '9024916297',
            'position'    => 'kevin.buchan@smu.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Saint Mary\'s University' => [
            'firstName'   => 'Kevin',
            'lastName'    => 'Buchan',
            'email'       => 'kevin.buchan@smu.ca',
            'telephone'   => '9024916297',
            'position'    => 'Director',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'St. Francis Xavier University' => [
            'firstName'   => 'Andrew',
            'lastName'    => 'Kendall',
            'email'       => 'akendall@stfx.ca',
            'telephone'   => '9028673660',
            'position'    => 'Manager',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'St. Thomas University' => [
            'firstName'   => 'Danielle',
            'lastName'    => 'Connell',
            'email'       => 'dconnell@stu.ca',
            'telephone'   => '5064520647',
            'position'    => 'Faculty Research Office Assistant',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'Université de Moncton' => [
            'firstName'   => 'Cassidy',
            'lastName'    => 'Weisbord',
            'email'       => 'cassidy.weisbord@umoncton.ca',
            'telephone'   => '5068584307',
            'position'    => 'Agent d\'innovation – Bureau de soutien à l’innovation',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'University of New Brunswick, Fredericton' => [
            'firstName'   => 'Alison',
            'lastName'    => 'MacNevin',
            'email'       => 'alison.macnevin@unb.ca',
            'telephone'   => '5064473296',
            'position'    => 'Knowledge Transfer Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'University of New Brunswick, Saint John' => [
            'firstName'   => 'Alison',
            'lastName'    => 'MacNevin',
            'email'       => 'alison.macnevin@unb.ca',
            'telephone'   => '5064473296',
            'position'    => 'Knowledge Transfer Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], 'University of Prince Edward Island' => [
            'firstName'   => 'Kimberley',
            'lastName'    => 'Johnstone',
            'email'       => 'kjohnstone@upei.ca',
            'telephone'   => '9026205115',
            'position'    => 'Technology Transfer & Industry Liaison Officer',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
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
