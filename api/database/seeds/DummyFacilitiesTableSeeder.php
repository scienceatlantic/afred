<?php
// !NOTE: Not abiding the 80 char column limit in this file.! 

use Illuminate\Database\Seeder;

use App\Facility;
use App\Contact;
use App\PrimaryContact;
use App\Equipment;
use App\FacilityRevisionHistory;
use Carbon\Carbon;

class DummyFacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = DB::table('organizations')
                           ->select('id')
                           ->get();
                           
        $provinces = DB::table('provinces')
                       ->select('id')
                       ->get();
                                
        $now = Carbon::now()->toDateTimeString();                 
        
        $facilities = [
            [
                'id'                        => null,
                'facilityRevisionHistoryId' => null,
                'organizationId'            => $organizations[0]->id,
                'provinceId'                => $provinces[0]->id,
                'name'                      => 'Biotech Lab',
                'city'                      => 'Wolfville',
                'website'                   => 'http://biotech.acadiau.ca',
                'description'               => 'Science!',
                'isPublic'                  => true,
                'dateSubmitted'             => $now,
                'dateUpdated'               => $now,
            ],
        ];
        
        $primaryContacts = [
            [
                'id'         => null,
                'facilityId' => null,
                'firstName'  => 'John',
                'lastName'   => 'Doe',
                'email'      => 'johndoe@example.com',
                'telephone'  => '9221332124',
                'extension'  => '',
                'position'   => 'Researcher'
            ]
        ];
                
        $contacts = [
            [
                'id'         => null,
                'facilityId' => null,
                'firstName'  => 'John',
                'lastName'   => 'Lennox',
                'email'      => 'johnl@example.com',
                'telephone'  => '8976665435',
                'extension'  => '3324',
                'position'   => 'Lab Assistant'
            ]
        ];
                
        $equipment = [
            [
                'id'                => null,
                'facilityId'        => null,
                'type'              => 'Magnetic resonance imaging (MRI)',
                'manufacturer'      => 'Hitachi Medical',
                'model'             => 'Echelon Oval 1.5T Ultra-Wide MRI '
                                     . 'system',
                'purpose'           => 'Medical imaging technique used in '
                                     . 'radiology to image the anatomy and the '
                                     . 'physiological processes of the body in '
                                     . 'both health and disease.',
                'specifications'    => '<ul>'
                                     . '    <li>1.5 Tesla high-field MRI with 74cm oval bore</li>'
                                     . '    <li>Short-bore, super-conductive magnet</li>'
                                     . '    <li>High homogeneity</li>'
                                     . '    <li>Zero Cryogen Boil-Off Technology</li>'
                                     . '    <li>34mT/m 150T/m/s gradients</li>'
                                     . '    <li>Optical RF system technology</li>'
                                     . '    <li>WIT mobile table</li>'
                                     . '</ul>',
                'isPublic'          => true,
                'hasExcessCapacity' => true
            ]
        ];
        
        $data = [];
        $data[0]['facility'] = $facilities[0];
        $data[0]['facility']['primaryContact'] = $primaryContacts[0];
        $data[0]['facility']['contacts'] = [$contacts[0]];
        $data[0]['facility']['equipment'] = [$equipment[0]];
        
        $frhs = [
            [
                'id'            => null,
                'facilityId'    => null,
                'state'         => 'PUBLISHED',
                'data'          => $data[0],
                'dateSubmitted' => $now
            ],
        ];
        
        foreach($frhs as $index => $frh) {
            $frhId = FacilityRevisionHistory::create($frh)->getKey();
            
            $facilities[0]['facilityRevisionHistoryId'] = $frhId;
            $facilityId = Facility::create($facilities[0])->getKey();
            
            $primaryContacts[0]['facilityId'] = $facilityId;
            PrimaryContact::create($primaryContacts[0]);
            
            foreach($contacts as $contact) {
                $contact['facilityId'] = $facilityId;
                Contact::create($contact);
            }
            
            foreach($equipment as $e) {
                $e['facilityId'] = $facilityId;
                Equipment::create($e);
            }
            
            $frh = FacilityRevisionHistory::find($frhId);
            $frh->facilityId = $facilityId;
            $frh->save();
        }
        
    }
}
