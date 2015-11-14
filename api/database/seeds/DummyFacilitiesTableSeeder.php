<?php

use Illuminate\Database\Seeder;

class DummyFacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the starting primary keys of institutions and provinces.
        // Using it as a reference point.
        $startingInstitutionId = DB::table('institutions')->select('id')->
            first();
        $startingProvinceId = DB::table('provinces')->select('id')->first();
        
        DB::table('facilities')->insert([
            [
                'institution_id' => $startingInstitutionId->id,
                'province_id' => $startingProvinceId->id,
                'name' => 'IONIC Laboratories',
                'city' => 'Wolfville',
                'website' => 'http://example.com',
                'description' => 'This facility does everything humanly' +
                    'possible',
                'is_public' => true
            ],
            [
                'institution_id' => $startingInstitutionId->id,
                'province_id' => $startingProvinceId->id + 1,
                'name' => 'Tomatojuice Lab',
                'city' => 'Halifax',
                'website' => 'http://example.com',
                'description' => 'We make a lot of tomato juice',
                'is_public' => true
            ],
        ]);
    }
}
