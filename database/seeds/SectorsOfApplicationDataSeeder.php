<?php

use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;
use Illuminate\Database\Seeder;

class SectorsOfApplicationDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            [
                'label' => 'Aerospace and Satellites'
            ], [
                'label' => 'Agriculture, Animal Science and Food'
            ], [
                'label' => 'Arts and Cultural Industries'
            ], [
                'label' => 'Automotive'
            ], [
                'label' => 'Biomedical'
            ], [
                'label' => 'Biotechnology'
            ], [
                'label' => 'Chemical Industries'
            ], [
                'label' => 'Clean Technology'
            ], [
                'label' => 'Construction (including Building, Civil Engineering, Specialty Trades)'
            ], [
                'label' => 'Consumer Durables'
            ], [
                'label' => 'Consumer Non-Durables'
            ], [
                'label' => 'Defense and Security Industries'
            ], [
                'label' => 'Education'
            ], [
                'label' => 'Energy (Renewable and Fossil)'
            ], [
                'label' => 'Environmental Technologies and Related Services'
            ], [
                'label' => 'Financial Services and Insurance'
            ], [
                'label' => 'Fisheries and Aquaculture'
            ], [
                'label' => 'Forestry and Forest-Based Industries'
            ], [
                'label' => 'Health Care and Social Services'
            ], [
                'label' => 'Information and Communication Technologies and Media'
            ], [
                'label' => 'Life Sciences, Pharmaceuticals and Medical Equipment'
            ], [
                'label' => 'Management and Business Related Services'
            ], [
                'label' => 'Manufacturing and Processing'
            ], [
                'label' => 'Mining, Minerals and Metals'
            ], [
                'label' => 'Ocean Industries'
            ], [
                'label' => 'Policy and Governance'
            ], [
                'label' => 'Professional and Technical Services (including Legal Services, Architecture, Engineering)'
            ], [
                'label' => 'Tourism and Hospitality'
            ], [
                'label' => 'Transportation'
            ], [
                'label' => 'Utilities'
            ]
        ];

        $languageCode = LanguageCode::where('iso_639_1', 'en')->first();

        $category = new LabelledValueCategory();
        $category->language_code_id = $languageCode->id;
        $category->name = 'Sectors of Application';
        $category->save();

        foreach($sectors as $sector) {
            $s = LabelledValue::where('label', $sector['label'])->first();
            if (!$s) {
                $s = new LabelledValue();
                $s->label = $sector['label'];
                $s->save();
            }
            $s->categories()->attach($category->id);
        }
    }
}
