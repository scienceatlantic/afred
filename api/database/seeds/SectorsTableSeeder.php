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
            'name'        => 'Aerospace and Satellites',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Agriculture, Animal Science and Food',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Arts and Cultural Industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Automotive',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Biomedical',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Biotechnology',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Chemical Industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Clean Technology',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Construction (including Building, Civil Engineering, Specialty Trades)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Consumer Durables',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Consumer Non-Durables',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Defense and Security Industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Education',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Energy (Renewable and Fossil)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Environmental Technologies and Related Services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Financial Services and Insurance',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Fisheries and Aquaculture',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Forestry and Forest-Based Industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Health Care and Social Services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Information and Communication Technologies and Media',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Life Sciences, Pharmaceuticals and Medical Equipment',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Management and Business Related Services',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Manufacturing and Processing',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Mining, Minerals and Metals',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Ocean Industries',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Policy and Governance',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Professional and Technical Services (including Legal Services, Architecture, Engineering)',
            'dateCreated' => $now,
            'dateUpdated' => $now            
        ], [
            'name'        => 'Tourism and Hospitality',
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
