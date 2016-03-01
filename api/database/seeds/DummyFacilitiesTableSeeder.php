<?php
// !NOTE: Not abiding the 80 char column limit in this file.! 

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

// Models.
use App\Facility;
use App\Contact;
use App\PrimaryContact;
use App\Equipment;
use App\FacilityRepository;

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
        
        // Facilities section.
        $facilities = [
            [
                'id'                   => null,
                'facilityRepositoryId' => null,
                'organizationId'       => $organizations[1]->id,
                'provinceId'           => $provinces[6]->id, // For NS.
                'name'                 => 'Biotech Lab',
                'city'                 => 'Wolfville',
                'website'              => 'http://biotech.acadiau.ca',
                'description'          => 'Science!',
                'isPublic'             => true,
                'dateSubmitted'        => $now,
                'dateUpdated'          => $now,
            ],
        ];
        
        // Disciplines section.
        $disciplines = [1, 2, 3, 4];
        
        // Sectors section.
        $sectors = [5, 6, 2, 3, 8];
        
        // Primary contacts section.
        $primaryContacts = [
            [
                'id'         => null,
                'facilityId' => null,
                'firstName'  => 'John',
                'lastName'   => 'Doe',
                'email'      => 'prasad@scienceatlantic.ca',
                'telephone'  => '9221332124',
                'extension'  => '',
                'position'   => 'Researcher'
            ]
        ];
                
        // Contacts section.
        $contacts = [
            [
                'id'         => null,
                'facilityId' => null,
                'firstName'  => 'John',
                'lastName'   => 'Lennox',
                'email'      => 'prasad.rajandran@scienceatlantic.ca',
                'telephone'  => '8976665435',
                'extension'  => '3324',
                'position'   => 'Lab Assistant'
            ]
        ];
        
        // Equipment section.
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
        
        // Facility repository section.
        $data = [];
        $data['facility'] = $facilities[0];
        $data['disciplines'] = $disciplines;
        $data['sectors'] = $sectors;
        $data['primaryContact'] = $primaryContacts[0];
        $data['contacts'] = [$contacts[0]];
        $data['equipment'] = [$equipment[0]];
        
        $fr = [
            'id'            => null,
            'facilityId'    => null,
            'state'         => 'PUBLISHED',
            'data'          => $data,
            'dateSubmitted' => $now
        ];
        
        // Create facility repository record.
        $fr = FacilityRepository::create($fr);
        
        $f = $fr->facility()->create($data['facility']);
        $data['facility'] = $f->toArray();
        
        // Create the discipline links.
        $f->disciplines()->attach($data['disciplines']);
        
        // Create the sector links.
        $f->sectors()->attach($data['sectors']);
        
        // Create primary contact record.
        $data['primaryContact'] = $f->primaryContact()
            ->create($data['primaryContact'])->toArray();
        
        // Create contact records.
        foreach($data['contacts'] as $i => $c) {
            $data['contacts'][$i] = $f->contacts()->create($c)->toArray();
        }
        
        // Create equipment record(s).
        foreach($data['equipment'] as $i => $e) {
            $data['equipment'] = $f->equipment()->create($e)->toArray();
        }
        
        // Update facility repository record.
        $fr->facilityId = $f->id;
        $fr->data = $data;
        $fr->save();
    }
}
