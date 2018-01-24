<?php

use App\Directory;
use App\Form;
use App\LabelledValueCategory;
use Faker\Factory as Faker;
use GuzzleHttp\Client as GuzzleHttp;
use Illuminate\Database\Seeder;

class DummyAfredFormDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $organizationIds = LabelledValueCategory::where('name', 'Organizations')
            ->first()
            ->values()
            ->get()
            ->pluck('id')
            ->toArray();
        $provinceIds = LabelledValueCategory::where('name', 'Canadian Provinces')
            ->first()
            ->values()
            ->get()
            ->pluck('id')
            ->toArray();
        $disciplineIds = LabelledValueCategory::where('name', 'Research Disciplines')
            ->first()
            ->values()
            ->get()
            ->pluck('id')
            ->toArray();
        $sectorIds = LabelledValueCategory::where('name', 'Sectors of Application')
            ->first()
            ->values()
            ->get()
            ->pluck('id')
            ->toArray();
        $form = Directory::where('shortname', 'AFRED')
            ->first()
            ->forms()
            ->where('name', 'Facilities')
            ->first();

        $nbFacilities = $faker->numberBetween(8, 18);
        for($index = 0; $index < $nbFacilities; $index++) {
            $facility = [[
                'name'         => $faker->words(3, true),
                'city'         => $faker->city,
                'organization' => $faker->randomElement($organizationIds),
                'province'     => $faker->randomElement($provinceIds),
                'website'      => $faker->url,
                'description'  => $faker->paragraphs(3, true),
                'disciplines'  => $faker->randomElements($disciplineIds, $faker->numberBetween(1, count($disciplineIds))),
                'sectors'      => $faker->randomElements($sectorIds, $faker->numberBetween(1, count($disciplineIds)))
            ]];
    
            $primaryContact = [[
                'firstName' => $faker->firstName,
                'lastName'  => $faker->lastName,
                'email'     => $faker->email,
                'telephone' => $faker->numberBetween(100000, 999999) . '' . $faker->numberBetween(1000, 9999),
                'position'  => $faker->word,
                'website'   => $faker->url,
                'extension' => $faker->numberBetween(1000, 9999)
            ]];
    
            $contacts = [];
            $nbContacts = $faker->numberBetween(1, 9);
            for($i = 0; $i < $nbContacts; $i++) {
                array_push($contacts, [
                    'firstName' => $faker->firstName,
                    'lastName'  => $faker->lastName,
                    'email'     => $faker->email,
                    'telephone' => $faker->numberBetween(100000, 999999) . '' . $faker->numberBetween(1000, 9999),
                    'position'  => $faker->word,
                    'website'   => $faker->url,
                    'extension' => $faker->numberBetween(1000, 9999)
                ]);
            }
    
            $equipment = [];
            $nbEquipment = $faker->numberBetween(1, 50);
            for($i = 0; $i < $nbEquipment; $i++) {
                array_push($equipment, [
                    'type'                    => $faker->word,
                    'model'                   => $faker->word,
                    'manufacturer'            => $faker->word,
                    'equipmentPurpose'        => $faker->paragraphs(3, true),
                    'equipmentSpecifications' => $faker->paragraphs(3, true),
                    'yearPurchased'           => $faker->numberBetween(1980, 2017),
                    'yearManufactured'        => $faker->numberBetween(1980, 2017)
                ]);
            }

            $url = "//localhost/afred/public/api/directories/{$form->directory->id}/forms/{$form->id}/entries";
            $http = new GuzzleHttp();
            $response = $http->post($url, [
                'query' => [
                    'action' => 'submit'
                ],
                'json' => [
                    'formIds' => Form::all()->pluck('id'),
                    'sections' => [
                        'facilities'      => $facility,
                        'primaryContacts' => $primaryContact,
                        'contacts'        => $contacts,
                        'equipment'       => $equipment
                    ]                    
                ]
            ]);
        }
    }
}
