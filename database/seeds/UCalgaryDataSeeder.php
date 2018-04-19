<?php

class UCalgaryDataSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Faculties and Departments
        $faculties = [
            [
                'label' => 'Anaesthesia (Department)'
            ], [
                'label' => 'Anthropology and Archaeology (Department)'
            ], [
                'label' => 'Architecture (Department)'
            ], [
                'label' => 'Art (Department)'
            ], [
                'label' => 'Arts (Faculty)'
            ], [
                'label' => 'Biochemistry and Molecular Biology (Department)'
            ], [
                'label' => 'Biological Sciences (Department)'
            ], [
                'label' => 'Cardiac Sciences (Department)'
            ], [
                'label' => 'Cell Biology and Anatomy (Department)'
            ], [
                'label' => 'Chemical and Petroleum Engineering (Department)'
            ], [
                'label' => 'Chemistry (Department)'
            ], [
                'label' => 'Civil Engineering (Department)'
            ], [
                'label' => 'Classics and Religion (Department)'
            ], [
                'label' => 'Clinical Neurosciences (Department)'
            ], [
                'label' => 'Communication, Media and Film (Department)'
            ], [
                'label' => 'Community Health Sciences (Department)'
            ], [
                'label' => 'Computer Science (Department)'
            ], [
                'label' => 'Critical Care Medicine (Department)'
            ], [
                'label' => 'Cumming School of Medicine (Faculty)'
            ], [
                'label' => 'Economics (Department)'
            ], [
                'label' => 'Electrical and Computer Engineering (Department)'
            ], [
                'label' => 'Emergency Medicine (Department)'
            ], [
                'label' => 'English (Department)'
            ], [
                'label' => 'Environmental Design (EVDS) (Faculty)'
            ], [
                'label' => 'Family Medicine (Department)'
            ], [
                'label' => 'Geography (Department)'
            ], [
                'label' => 'Geomatics Engineering (Department)'
            ], [
                'label' => 'Geoscience (Department)'
            ], [
                'label' => 'Haskayne School of Business (Faculty)'
            ], [
                'label' => 'History (Department)'
            ], [
                'label' => 'Kinesiology (Faculty)'
            ], [
                'label' => 'Landscape Architecture (Department)'
            ], [
                'label' => 'Law (Faculty)'
            ], [
                'label' => 'Mathematics & Statistics (Department)'
            ], [
                'label' => 'Mechanical and Manufacturing Engineering (Department)'
            ], [
                'label' => 'Medical Genetics (Department)'
            ], [
                'label' => 'Medicine (Department)'
            ], [
                'label' => 'Microbiology, Immunology and Infectious Diseases (Department)'
            ], [
                'label' => 'Neuroscience (Department)'
            ], [
                'label' => 'Nursing (Faculty)'
            ], [
                'label' => 'Obstetrics and Gynaecology (Department)'
            ], [
                'label' => 'Oncology (Department)'
            ], [
                'label' => 'Paediatrics (Department)'
            ], [
                'label' => 'Pathology and Laboratory Medicine (Department)'
            ], [
                'label' => 'Philosophy (Department)'
            ], [
                'label' => 'Physics & Astronomy (Department)'
            ], [
                'label' => 'Physiology and Pharmacology (Department)'
            ], [
                'label' => 'Planning (Department)'
            ], [
                'label' => 'Political Science (Department)'
            ], [
                'label' => 'Psychiatry (Department)'
            ], [
                'label' => 'Psychology (Department)'
            ], [
                'label' => 'Radiology (Department)'
            ], [
                'label' => 'Schulich School of Engineering (Faculty)'
            ], [
                'label' => 'Science (Faculty)'
            ], [
                'label' => 'Social Work (Faculty)'
            ], [
                'label' => 'Sociology (Department)'
            ], [
                'label' => 'Surgery (Department)'
            ], [
                'label' => 'Veterinary Medicine (Faculty)'
            ], [
                'label' => 'Werklund School of Education (Faculty)'
            ]
        ];

        $category = self::saveCategory('University of Calgary Faculties and Departments');
        self::saveLabelledValues($faculties, [$category->id]);

        // Add UofT
        self::saveLabelledValues([[
            'label' => 'University of Calgary'
        ]]);
    }
}
