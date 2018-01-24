<?php

use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;
use Illuminate\Database\Seeder;

class ResearchDisciplinesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disciplines = [
            [
                'label' => 'Astronomy and Physics'
            ], [
                'label' => 'Biological and Life Sciences'
            ], [
                'label' => 'Biomedical'
            ], [
                'label' => 'Biotechnology'
            ], [
                'label' => 'Business'
            ], [
                'label' => 'Chemistry and Biochemistry'
            ], [
                'label' => 'Computer Science and Software Engineering'
            ], [
                'label' => 'Engineering - Agricultural, Forest, Environmental, Mining, Mineral'
            ], [
                'label' => 'Engineering - Chemical, Nuclear, Other'
            ], [
                'label' => 'Engineering - Civil, Structural'
            ], [
                'label' => 'Engineering - Industrial, Mechanical, Electrical'
            ], [
                'label' => 'Environmental and Earth Sciences'
            ], [
                'label' => 'Geomatics and Geodesy'
            ], [
                'label' => 'Humanities and Social Sciences'
            ], [
                'label' => 'Marine/Ocean Sciences'
            ], [
                'label' => 'Mathematics and Statistics'
            ], [
                'label' => 'Medical Sciences'
            ], [
                'label' => 'Psychology'
            ], [
                'label' => 'Veterinary Sciences'
            ]
        ];

        $languageCode = LanguageCode::where('iso_639_1', 'en')->first();

        $category = new LabelledValueCategory();
        $category->language_code_id = $languageCode->id;
        $category->name = 'Research Disciplines';
        $category->save();

        foreach($disciplines as $discipline) {
            $d = LabelledValue::where('label', $discipline['label'])->first();
            if (!$d) {
                $d = new LabelledValue();
                $d->label = $discipline['label'];
                $d->save();
            }
            $d->categories()->attach($category->id);
        }        
    }
}
