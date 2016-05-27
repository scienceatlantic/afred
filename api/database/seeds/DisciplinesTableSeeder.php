<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Discipline;

class DisciplinesTableSeeder extends Seeder
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
        DB::table('disciplines')->delete();
        
        $disciplines = [[
            'name'        => 'Astronomy and Physics',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Biological and Life Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Biotechnology',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Biomedical',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Business',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Chemistry and Biochemistry',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Computer Science and Software Engineering',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Agricultural, Forest, Environmental, Mining, Mineral',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Chemical, Nuclear, Other',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Civil, Structural',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Industrial, Mechanical, Electrical',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Environmental and Earth Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Geomatics and Geodesy',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Humanities and Social Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Marine/Ocean Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Mathematics and Statistics',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Medical Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Psychology',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Veterinary Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
        foreach($disciplines as $discipline) {
            Discipline::create($discipline);
        }
    }
}
