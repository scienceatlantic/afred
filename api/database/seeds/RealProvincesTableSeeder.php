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
            ['name' => 'Alberta', 'is_hidden' => true],
            ['name' => 'British Columbia', 'is_hidden' => true],
            ['name' => 'Manitoba', 'is_hidden' => true],
            ['name' => 'New Brunswick', 'is_hidden' => false],
            ['name' => 'Newfoundland and Labrador', 'is_hidden' => false],
            ['name' => 'Northwest Territories', 'is_hidden' => true],
            ['name' => 'Nova Scotia', 'is_hidden' => false],
            ['name' => 'Nunavut', 'is_hidden' => true],
            ['name' => 'Ontario', 'is_hidden' => true],
            ['name' => 'Prince Edward Island', 'is_hidden' => false],
            ['name' => 'Quebec', 'is_hidden' => true],
            ['name' => 'Saskatchewan', 'is_hidden' => true],
            ['name' => 'Yukon', 'is_hidden' => true]
        ]);
    }
}
