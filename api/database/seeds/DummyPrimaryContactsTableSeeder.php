<?php

use Illuminate\Database\Seeder;

class DummyPrimaryContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the starting primary key of facility. Use it as a reference
        // point.
        $startingFacilityId = DB::table('facilities')->select('id')->first();
        
        DB::table('primary_contacts')->insert([
            [
                'facilityId' => $startingFacilityId->id,
                'firstName'  => 'Michael',
                'lastName'   => 'Doe',
                'email'      => 'michaeldoe@gmail.com',
                'telephone'  => '9999999999',
                'extension'  => '0928',
                'position'   => 'Doubly Lord Commander',
                'website'    => 'http://example.com'
            ],
        ]);
    }
}
