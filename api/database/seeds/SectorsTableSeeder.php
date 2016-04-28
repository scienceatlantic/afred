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
        
        $sectors = [[
            'name'        => 'Aerospace and satellites',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Agriculture, animal science and food',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Arts and cultural industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Automotive',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Biotechnology/Biomedical',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Chemical industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Clean technology',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Construction (including building, civil engineering, specialty trades)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Consumer durables',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Consumer non-durables',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Defense and security industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Education',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Energy (renewable and fossil)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Environmental technologies and related services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Financial services and insurance',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Fisheries and aquaculture',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Forestry and forest-based industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Health care and social services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Information and communication technologies and media',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Life sciences, pharmaceuticals and medical equipment',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Management and business related services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Manufacturing and processing',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Mining, minerals and metals',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Ocean industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Policy and governance',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Professional and technical services (including legal services, architecture, engineering)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Tourism and hospitality',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Transportation',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Utilities',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ]];
        
        foreach($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
