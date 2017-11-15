<?php

use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;
use Illuminate\Database\Seeder;

class ProvinceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            [
                'label' => 'Alberta (AB)'
            ], [
                'label' => 'British Columbia (BC)'
            ], [
                'label' => 'Manitoba (MB)'
            ], [
                'label' => 'New Brunswick (NB)'
            ], [
                'label' => 'Newfoundland and Labrador (NL)'
            ], [
                'label' => 'Nova Scotia (NS)'
            ], [
                'label' => 'Ontario (ON)'
            ], [
                'label' => 'Prince Edward Island (PE)'
            ], [
                'label' => 'Quebec (QC)'
            ], [
                'label' => 'Saskatchewan (SK)'
            ]
        ];

        $languageCode = LanguageCode::where('iso_639_1', 'en')->first();

        $category = new LabelledValueCategory();
        $category->language_code_id = $languageCode->id;
        $category->name = 'Canadian Provinces';
        $category->save();

        foreach($provinces as $province) {
            $p = LabelledValue::where('label', $province['label'])->first();
            if (!$p) {
                $p = new LabelledValue();
                $p->label = $province['label'];
                $p->save();
            }
            $p->categories()->attach($category->id);
        }
    }
}
