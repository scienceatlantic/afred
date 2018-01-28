<?php

class OrganizationDataSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organisations = [
            [
                'label' => 'N/A'
            ], [
                'label' => 'Acadia University'
            ], [
                'label' => 'Cape Breton University'
            ], [
                'label' => 'Collège communautaire du Nouveau-Brunswick'
            ], [
                'label' => 'College of the North Atlantic'
            ], [
                'label' => 'Crandall University'
            ], [
                'label' => 'Dalhousie University'
            ], [
                'label' => 'Dalhousie University Faculty of Agriculture'
            ], [
                'label' => 'Holland College'
            ], [
                'label' => 'Memorial University'
            ], [
                'label' => 'Memorial University Grenfell Campus'
            ], [
                'label' => 'Mount Allison University'
            ], [
                'label' => 'Mount Saint Vincent University'
            ], [
                'label' => 'New Brunswick Community College'
            ], [
                'label' => 'Nova Scotia Community College'
            ], [
                'label' => 'NSCAD University'
            ], [
                'label' => 'Saint Mary\'s University'
            ], [
                'label' => 'St. Francis Xavier University'
            ], [
                'label' => 'St. Thomas University'
            ], [
                'label' => 'Université de Moncton'
            ], [
                'label' => 'University of New Brunswick Fredericton'
            ], [
                'label' => 'University of New Brunswick Saint John'
            ], [
                'label' => 'University of Prince Edward Island'
            ], [
                'label' => 'Falck Safety Services Canada'
            ], [
                'label' => 'Cape Breton Fish Harvesters Association'
            ], [
                'label' => 'NSHA & IWK'
            ], [
                'label' => 'Leeway Marine'
            ], [
                'label' => 'Luna Ocean Consulting Ltd.'
            ], [
                'label' => 'Coastal zones research institute'
            ]
        ];

        $category = self::saveCategory('Organizations');
        self::saveLabelledValues($organisations, [$category->id]);
    }
}
