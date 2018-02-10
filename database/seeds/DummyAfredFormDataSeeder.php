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

        $organizationIds = LabelledValueCategory
            ::findCategory('Organizations')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        $provinceIds = LabelledValueCategory
            ::findCategory('Canadian Provinces')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        $disciplineIds = LabelledValueCategory
            ::findCategory('Research Disciplines')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        $sectorIds = LabelledValueCategory
            ::findCategory('Sectors of Application')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        $searchVisibilityIds = LabelledValueCategory
            ::findCategory('Search visibility')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        $excessCapacityIds = LabelledValueCategory
            ::findCategory('Excess capacity')
            ->values()
            ->pluck('labelled_values.id')
            ->toArray();
        
        $form = Directory
            ::findDirectory('Atlantic Facilities and Research Equipment Database')
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
                'disciplines'  => $faker->randomElements(
                    $disciplineIds,
                    $faker->numberBetween(1, count($disciplineIds))
                ),
                'sectors'      => $faker->randomElements(
                    $sectorIds,
                    $faker->numberBetween(1, count($disciplineIds))
                )
            ]];
    
            $primaryContact = [[
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->email,
                'telephone'  => $faker->numberBetween(100000, 999999) 
                             . '' 
                             . $faker->numberBetween(1000, 9999),
                'position'   => $faker->word,
                'website'    => $faker->url,
                'extension'  => $faker->numberBetween(1000, 9999)
            ]];
    
            $contacts = [];
            $nbContacts = $faker->numberBetween(1, 9);
            for($i = 0; $i < $nbContacts; $i++) {
                array_push($contacts, [
                    'first_name' => $faker->firstName,
                    'last_name'  => $faker->lastName,
                    'email'      => $faker->email,
                    'telephone'  => $faker->numberBetween(100000, 999999)
                                 . '' 
                                 . $faker->numberBetween(1000, 9999),
                    'position'   => $faker->word,
                    'website'    => $faker->url,
                    'extension'  => $faker->numberBetween(1000, 9999)
                ]);
            }
    
            $equipment = [];
            $nbEquipment = $faker->numberBetween(1, 50);
            for($i = 0; $i < $nbEquipment; $i++) {
                array_push($equipment, [
                    'type'              => $faker->word,
                    'model'             => $faker->word,
                    'manufacturer'      => $faker->word,
                    'purpose'           => $faker->paragraphs(3, true),
                    'specifications'    => $faker->paragraphs(3, true),
                    'year_purchased'    => $faker->numberBetween(1980, 2017),
                    'year_manufactured' => $faker->numberBetween(1980, 2017),
                    'search_visibility' => $faker->randomElements(
                        $searchVisibilityIds,
                        $faker->numberBetween(1, count($searchVisibilityIds))
                    ),
                    'excess_capacity'   => $faker->randomElements(
                        $excessCapacityIds,
                        $faker->numberBetween(1, count($excessCapacityIds))
                    )
                ]);
            }

            //TODO:
            $url = "//localhost/afred/public/api/directories/{$form->directory->id}/forms/{$form->id}/entries";

            $response = (new GuzzleHttp())->post($url, [
                'query' => [
                    'action' => 'submit'
                ],
                'json' => [
                    'forms' => Form::all()->pluck('id'),
                    'sections' => [
                        'facilities'       => $facility,
                        'primary_contacts' => $primaryContact,
                        'contacts'         => $contacts,
                        'equipment'        => $equipment
                    ]
                ]
            ]);
        }
    }
}
