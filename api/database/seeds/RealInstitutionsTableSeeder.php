<?php

use Illuminate\Database\Seeder;

class RealInstitutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries.
        DB::table('institutions')->delete();
        
        DB::table('institutions')->insert([
            ['name' => 'N/A', 'is_hidden' => 'false'],
            ['name' => 'Acadia University', 'is_hidden' => 'false'],
            ['name' => 'Cape Breton University', 'is_hidden' => 'false'],
            ['name' => 'Crandall University', 'is_hidden' => 'false'],
            ['name' => 'Dalhousie University', 'is_hidden' => 'false'],
            ['name' => 'Dalhousie University, Faculty of Agriculture',
                'is_hidden' => 'false'],
            ['name' => 'Memorial University', 'is_hidden' => 'false'],
            ['name' => 'Memorial University, Grenfell Campus',
                'is_hidden' => 'false'],
            ['name' => 'Mount Allison University', 'is_hidden' => 'false'],
            ['name' => 'Mount Saint Vincent University',
                'is_hidden' => 'false'],
            ['name' => 'Saint Mary\'s University', 'is_hidden' => 'false'],
            ['name' => 'St. Francis Xavier University',
                'is_hidden' => 'false'],
            ['name' => 'St. Thomas University', 'is_hidden' => 'false'],
            ['name' => 'Université de Moncton', 'is_hidden' => 'false'], //FIX THE ACCENTED E!
            ['name' => 'University of New Brunswick, Fredericton',
                'is_hidden' => 'false'],
            ['name' => 'University of New Brunswick, Saint John',
                'is_hidden' => 'false'],
            ['name' => 'University of Prince Edward Island',
                'is_hidden' => 'false'],
        ]);
    }
}
