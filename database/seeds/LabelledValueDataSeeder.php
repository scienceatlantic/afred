<?php

class LabelledValueDataSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typicalValues = [
            [
                'label' => 'N/A'
            ], [
                'label' => 'Other'
            ]
        ];

        self::saveLabelledValues($typicalValues);
    }
}
