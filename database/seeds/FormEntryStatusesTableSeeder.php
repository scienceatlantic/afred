<?php

class FormEntryStatusesTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name'              => 'Submitted',
                'show_in_dropdown'  => true
            ], [
                'name'              => 'Published',
                'show_in_dropdown'  => true
            ], [
                'name'              => 'Rejected',
                'show_in_dropdown'  => true
            ], [
                'name'              => 'Revision',
                'show_in_dropdown'  => false
            ], [
                'name'              => 'Deleted',
                'show_in_dropdown'  => true
            ], [
                'name'              => 'Hidden',
                'show_in_dropdown'  => true
            ]
        ];

        self::saveModels('FormEntryStatus', $statuses);
    }
}
