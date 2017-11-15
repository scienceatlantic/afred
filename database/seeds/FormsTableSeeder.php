<?php

use App\Form;
use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
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

        foreach($forms as $form) {
            $f = new Form();
            $f->directory_id = $form['directory_id'];
            $f->save();
        }
    }
}
