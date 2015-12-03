<?php

use Illuminate\Database\Seeder;

class RealProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries
        DB::table('provinces')->delete();
        
        DB::table('provinces')->insert([
            ['name' => 'Alberta', 'isHidden' => true],
            ['name' => 'British Columbia', 'isHidden' => true],
            ['name' => 'Manitoba', 'isHidden' => true],
            ['name' => 'New Brunswick', 'isHidden' => false],
            ['name' => 'Newfoundland and Labrador', 'isHidden' => false],
            ['name' => 'Northwest Territories', 'isHidden' => true],
            ['name' => 'Nova Scotia', 'isHidden' => false],
            ['name' => 'Nunavut', 'isHidden' => true],
            ['name' => 'Ontario', 'isHidden' => true],
            ['name' => 'Prince Edward Island', 'isHidden' => false],
            ['name' => 'Quebec', 'isHidden' => true],
            ['name' => 'Saskatchewan', 'isHidden' => true],
            ['name' => 'Yukon', 'isHidden' => true]
        ]);
    }
}
