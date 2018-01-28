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
                'name' => 'Draft'
            ], [
                'name' => 'Submitted'
            ], [
                'name' => 'Published'
            ], [
                'name' => 'Rejected'
            ], [
                'name' => 'Past'
            ], [
                'name' => 'Deleted'
            ]
        ];

        self::saveModels('FormEntryStatus', $statuses);
    }
}
