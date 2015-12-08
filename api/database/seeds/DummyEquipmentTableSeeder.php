<?php

use Illuminate\Database\Seeder;
use App\Equipment;

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
        
        $equipment = [
            [
                'facilityId'        => $startingFacilityId->id,
                'type'              => 'Magnetic resonance imaging (MRI)',
                'manufacturer'      => 'Hitachi Medical',
                'model'             => 'Echelon Oval 1.5T Ultra-Wide MRI '
                                     . 'system',
                'purpose'           => 'Medical imaging technique used in '
                                     . 'radiology to image the anatomy and the '
                                     . 'physiological processes of the body in '
                                     . 'both health and disease.',
                'specifications'    => '<ul>'
                                     . '<li>1.5 Tesla high-field MRI with 74cm '
                                     . 'oval bore</li>'
                                     . '<li>Short-bore, super-conductive '
                                     . 'magnet</li>'
                                     . '<li>High homogeneity</li>'
                                     . '<li>Zero Cryogen Boil-Off Technology'
                                     . '</li>'
                                     . '<li>34mT/m 150T/m/s gradients</li>'
                                     . '<li>Optical RF system technology</li>'
                                     . '<li>WIT mobile table</li>'
                                     . '</ul>',
                'isPublic'          => true,
                'hasExcessCapacity' => true
            ],
        ];
        
        foreach($equipment as $e) {
            Equipment::create($e);
        }
    }
}
