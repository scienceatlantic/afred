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
                'label' => 'Public'
            ], [
                'label' => 'Private'
            ]
        ];

        $category = self::saveCategory('Search visibility');
        self::saveLabelledValues($publicOrPrivate, [$category->id]);
    }
}
