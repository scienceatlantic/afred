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
        
        $organizations = [[
            'name'        => 'N/A',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Acadia University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Cape Breton University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Collège communautaire du Nouveau-Brunswick',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'College of the North Atlantic',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Crandall University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Dalhousie University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Dalhousie University, Faculty of Agriculture',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Holland College',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Memorial University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Memorial University, Grenfell Campus',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Mount Allison University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Mount Saint Vincent University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'New Brunswick Community College',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Nova Scotia Community College',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'NSCAD University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Saint Mary\'s University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'St. Francis Xavier University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'St. Thomas University',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Université de Moncton',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'University of New Brunswick, Fredericton',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'University of New Brunswick, Saint John',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'University of Prince Edward Island',
            'isHidden'    => false,
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
        foreach($organizations as $organization) {
            Organization::create($organization);
        }
    }
}
