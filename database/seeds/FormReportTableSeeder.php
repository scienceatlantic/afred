<?php

class FormReportTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reports = [
            [
                'form_id'        => 1,
                'name'           => 'Approved equipment',
                'filename'       => 'Approved equipment',
                'report_columns' => 'facilities.0.name,equipment.*.type'
            ]
        ];

        self::saveModels('FormReport', $reports);
    }
}
