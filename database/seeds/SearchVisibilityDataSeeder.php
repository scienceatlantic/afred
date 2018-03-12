<?php

class SearchVisibilityDataSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $publicOrPrivate = [
            [
                'label' => 'Private'
            ], [
                'label' => 'Public'
            ]
        ];

        $category = self::saveCategory('Search visibility');
        self::saveLabelledValues($publicOrPrivate, [$category->id]);
    }
}
