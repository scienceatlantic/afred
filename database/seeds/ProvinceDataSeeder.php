<?php

class ProvinceDataSeeder extends BaseSeeder
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
                'label' => 'Northwest Territories (NT)'
            ], [
                'label' => 'Nova Scotia (NS)'
            ], [
                'label' => 'Nunavut (NU)'
            ], [
                'label' => 'Ontario (ON)'
            ], [
                'label' => 'Prince Edward Island (PE)'
            ], [
                'label' => 'Quebec (QC)'
            ], [
                'label' => 'Saskatchewan (SK)'
            ], [
                'label' => 'Yukon (YT)'
            ]
        ];

        $category = self::saveCategory('Canadian Provinces and Territories');
        self::saveLabelledValues($provinces, [$category->id]);
    }
}
