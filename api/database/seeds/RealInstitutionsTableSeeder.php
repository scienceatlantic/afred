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
            ['name' => 'N/A', 'isHidden' => 'false'],
            ['name' => 'Acadia University', 'isHidden' => 'false'],
            ['name' => 'Cape Breton University', 'isHidden' => 'false'],
            ['name' => 'Crandall University', 'isHidden' => 'false'],
            ['name' => 'Dalhousie University', 'isHidden' => 'false'],
            ['name' => 'Dalhousie University, Faculty of Agriculture',
                'isHidden' => 'false'],
            ['name' => 'Memorial University', 'isHidden' => 'false'],
            ['name' => 'Memorial University, Grenfell Campus',
                'isHidden' => 'false'],
            ['name' => 'Mount Allison University', 'isHidden' => 'false'],
            ['name' => 'Mount Saint Vincent University',
                'isHidden' => 'false'],
            ['name' => 'Saint Mary\'s University', 'isHidden' => 'false'],
            ['name' => 'St. Francis Xavier University',
                'isHidden' => 'false'],
            ['name' => 'St. Thomas University', 'isHidden' => 'false'],
            ['name' => 'Université de Moncton', 'isHidden' => 'false'], //FIX THE ACCENTED E!
            ['name' => 'University of New Brunswick, Fredericton',
                'isHidden' => 'false'],
            ['name' => 'University of New Brunswick, Saint John',
                'isHidden' => 'false'],
            ['name' => 'University of Prince Edward Island',
                'isHidden' => 'false'],
        ]);
    }
}
