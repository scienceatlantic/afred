<?php

class SearchFacetOperatorTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operators = [
            [
                'name' => 'AND'
            ], [
                'name' => 'OR'
            ], [
                'name' => 'NOT'
            ]
        ];

        self::saveModels('SearchFacetOperator', $operators);
    }
}
