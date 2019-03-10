<?php

class RolesTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'  => 'Administrator',
                'level' => 10,
            ], [
                'name'  => 'Editor',
                'level' => 9
            ], [
                'name'  => 'Author',
                'level' => 8
            ], [
                'name'  => 'Contributor',
                'level' => 7
            ], [
                'name'  => 'Subscriber',
                'level' => 6
            ], [
                'name'  => 'Database User',
                'level' => 5
            ]
        ];

        self::saveModels('Role', $roles);
    }
}
