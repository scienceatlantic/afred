<?php

use App\FormFieldType;
use Illuminate\Database\Seeder;

class FormFieldTypesTableSeeder extends Seeder
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

        foreach($types as $type) {
            $f = new FormFieldType();
            $f->name = $type['name'];
            $f->save();
        }
    }
}
