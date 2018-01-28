<?php

class FormsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forms = [
            [
                'directory_id' => 1
            ]
        ];

        self::saveModels('Form', $forms);
    }
}
