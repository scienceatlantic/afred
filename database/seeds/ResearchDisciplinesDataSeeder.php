<?php

class ResearchDisciplinesDataSeeder extends BaseSeeder
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

        $category = self::saveCategory('Research Disciplines');
        self::saveLabelledValues($disciplines, [$category->id]);
    }
}
