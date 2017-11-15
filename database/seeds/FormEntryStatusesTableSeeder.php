<?php

use App\FormEntryStatus;
use Illuminate\Database\Seeder;

class FormEntryStatusesTableSeeder extends Seeder
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

        foreach($statuses as $status) {
            $f = new FormEntryStatus();
            $f->name = $status['name'];
            $f->save();
        }
    }
}
