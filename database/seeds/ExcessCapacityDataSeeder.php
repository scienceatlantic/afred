<?php

class ExcessCapacityDataSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yesOrNo = [
            [
                'label' => 'Yes'
            ], [
                'label' => 'No'
            ]
        ];

        $category = self::saveCategory('Excess capacity');
        self::saveLabelledValues($yesOrNo, [$category->id]);
    }
}
