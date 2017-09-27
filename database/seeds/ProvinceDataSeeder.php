<?php

use App\FormFieldDropdownValue;
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
                'form_label' => 'Alberta (AB)'
            ], [
                'form_label' => 'British Columbia (BC)'
            ], [
                'form_label' => 'Manitoba (MB)'
            ], [
                'form_label' => 'New Brunswick (NB)'
            ], [
                'form_label' => 'Newfoundland and Labrador (NL)'
            ], [
                'form_label' => 'Nova Scotia (NS)'
            ], [
                'form_label' => 'Ontario (ON)'
            ], [
                'form_label' => 'Prince Edward Island (PE)'
            ], [
                'form_label' => 'Quebec (QC)'
            ], [
                'form_label' => 'Saskatchewan (SK)'
            ]
        ];

        foreach($provinces as $province) {
            $p = new FormFieldDropdownValue();
            $p->form_label = $province['form_label'];
            $p->save();
        }
    }
}
