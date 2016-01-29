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
        
        $disciplines = [
            [
                'name'      => 'Health Sciences',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Engineering',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Biology',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Chemistry',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Physics',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Environmental Sciences',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Ocean Sciences',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Forestry',
                'dateAdded' => $now
            ],
            [
                'name'      => 'Computer Science',
                'dateAdded' => $now
            ]
        ];
        
        foreach($disciplines as $discipline) {
            Discipline::create($discipline);
        }
    }
}
