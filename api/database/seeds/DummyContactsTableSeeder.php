<?php

use Illuminate\Database\Seeder;
use App\Contact;

class DummyContactsTableSeeder extends Seeder
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
        
        $contacts = [
            [
                'facilityId' => $startingFacilityId->id,
                'firstName'  => 'John',
                'lastName'   => 'Doe',
                'email'      => 'johndoe@gmail.com',
                'telephone'  => '9999999999',
                'extension'  => '543',
                'position'   => 'Lord Commander',
                'website'    => 'http://example.com'
            ],
        ];
        
        foreach($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
