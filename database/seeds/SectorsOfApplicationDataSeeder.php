<?php

class SectorsOfApplicationDataSeeder extends BaseSeeder
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

        $category = self::saveCategory('Sectors of Application');
        self::saveLabelledValues($sectors, [$category->id]);
    }
}
