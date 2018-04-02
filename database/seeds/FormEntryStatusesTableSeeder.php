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
                'is_distinct'  => true
            ], [
                'name'              => 'Published',
                'is_distinct'  => true
            ], [
                'name'              => 'Rejected',
                'is_distinct'  => true
            ], [
                'name'              => 'Revision',
                'is_distinct'  => false
            ], [
                'name'              => 'Deleted',
                'is_distinct'  => true
            ], [
                'name'              => 'Hidden',
                'is_distinct'  => true
            ]
        ];

        self::saveModels('FormEntryStatus', $statuses);
    }
}
