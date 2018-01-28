<?php

class FieldTypesTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'string'
            ], [
                'name' => 'plaintext'
            ], [
                'name' => 'richtext'
            ], [
                'name' => 'number'
            ], [
                'name' => 'date'
            ], [
                'name' => 'radio'
            ], [
                'name' => 'checkbox'
            ], [
                'name' => 'dropdown'
            ]
        ];

        self::saveModels('FieldType', $types);
    }
}
