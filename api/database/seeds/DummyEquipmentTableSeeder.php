<?php

use Illuminate\Database\Seeder;

class DummyEquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the starting primary key of facility. Using it as a reference
        // point.
        $startingFacilityId = DB::table('facilities')->select('id')->first();
        
        DB::table('equipment')->insert([
            [
                'facilityId' => $startingFacilityId->id,
                'type' => 'Fruit',
                'manufacturer' => 'Mother nature',
                'model' => 'Tomatoes',
                'purpose' => 'Human consumption',
                'specifications' => '18 calories per 100 grams.',
                'isPublic' => true,
                'hasExcessCapacity' => true
            ],
        ]);
    }
}
