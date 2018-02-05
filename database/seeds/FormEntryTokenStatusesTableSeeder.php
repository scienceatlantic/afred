<?php

class FormEntryTokenStatusesTableSeeder extends BaseSeeder
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
                'name' => 'Open'
            ], [
                'name' => 'Locked'
            ], [
                'name' => 'Closed'
            ]
        ];

        self::saveModels('FormEntryTokenStatus', $statuses);
    }
}
