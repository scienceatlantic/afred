<?php

use Illuminate\Database\Seeder;
use App\Facility;
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
        // Get the starting primary keys of institutions and provinces.
        // Using it as a reference point.
        $startingInstitutionId = DB::table('institutions')
                                   ->select('id')
                                   ->first();
        $startingProvinceId = DB::table('provinces')
                                ->select('id')
                                ->first();
        
        $facilities = [
            [
                'institutionId' => $startingInstitutionId->id,
                'provinceId'    => $startingProvinceId->id,
                'name'          => 'IONIC Laboratories',
                'city'          => 'Wolfville',
                'website'       => 'http://example.com',
                'description'   => 'This facility does everything humanly'
                                 . 'possible',
                'isPublic'      => true,
                'dateSubmitted' => Carbon::now(),
                'dateUpdated'   => Carbon::now()
            ],
            [
                'institutionId' => $startingInstitutionId->id,
                'provinceId'    => $startingProvinceId->id + 1,
                'name'          => 'Tomatojuice Lab',
                'city'          => 'Halifax',
                'website'       => 'http://example.com',
                'description'   => 'We make a lot of tomato juice',
                'isPublic'      => true,
                'dateSubmitted' => Carbon::now(),
                'dateUpdated'   => Carbon::now()
            ],
        ];
        
        foreach($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
