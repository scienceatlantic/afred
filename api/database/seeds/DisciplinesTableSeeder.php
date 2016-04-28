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
            'name'        => 'Astronomy/Astrophysics/Physics',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Biological and Life Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Biotechnology and Biomedical',
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
            'name'        => 'Engineering - Agricultural, Forest, Environmental, Mining and Mineral',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Chemical, Nuclear, Other',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Engineering - Civil, Structural, Industrial, Mechanical, Electrical',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Environmental and Earth Science',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Geomatics',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Humanities and Social Science',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Marine/Ocean Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Mathematics/Statistics/Computer Sciences',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Medical Science',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Psychology',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'Veterinary Studies',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
        foreach($disciplines as $discipline) {
            Discipline::create($discipline);
        }
    }
}
