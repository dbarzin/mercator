<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoBuildingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('buildings')->delete();

        \DB::table('buildings')->insert([
            0 => [
                'id' => 1,
                'name' => 'Building 1',
                'description' => '<p>Description du building 1</p>',
                'created_at' => '2020-06-21 06:37:21',
                'updated_at' => '2020-06-21 06:47:41',
                'deleted_at' => null,
                'site_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Building 2',
                'description' => '<p>Description du building 2</p>',
                'created_at' => '2020-06-21 06:37:36',
                'updated_at' => '2020-07-25 08:26:13',
                'deleted_at' => null,
                'site_id' => 1,
            ],
            2 => [
                'id' => 3,
                'name' => 'Building 3',
                'description' => '<p>Description du building 3</p>',
                'created_at' => '2020-06-21 06:37:48',
                'updated_at' => '2020-07-25 08:26:03',
                'deleted_at' => null,
                'site_id' => 2,
            ],
            3 => [
                'id' => 4,
                'name' => 'Building 4',
                'description' => '<p>Description du building 4</p>',
                'created_at' => '2020-06-21 06:38:03',
                'updated_at' => '2020-07-25 08:25:54',
                'deleted_at' => null,
                'site_id' => 2,
            ],
            4 => [
                'id' => 5,
                'name' => 'Building 5',
                'description' => '<p>Descripion du building 5</p>',
                'created_at' => '2020-06-21 06:38:16',
                'updated_at' => '2020-07-25 08:26:26',
                'deleted_at' => null,
                'site_id' => 3,
            ],
            5 => [
                'id' => 6,
                'name' => 'Test building',
                'description' => '<p>Description</p>',
                'created_at' => '2020-07-24 21:12:48',
                'updated_at' => '2020-07-24 21:14:08',
                'deleted_at' => '2020-07-24 21:14:08',
                'site_id' => 4,
            ],
            6 => [
                'id' => 7,
                'name' => 'Building 0',
                'description' => '<p>Le building zéro</p>',
                'created_at' => '2020-08-21 15:10:15',
                'updated_at' => '2020-10-02 09:38:55',
                'deleted_at' => null,
                'site_id' => 1,
            ],
            7 => [
                'id' => 8,
                'name' => 'test',
                'description' => '<p>test</p>',
                'created_at' => '2020-11-06 14:44:22',
                'updated_at' => '2020-11-06 15:26:18',
                'deleted_at' => '2020-11-06 15:26:18',
                'site_id' => null,
            ],
            8 => [
                'id' => 9,
                'name' => 'test2',
                'description' => '<p>test2</p>',
                'created_at' => '2020-11-06 14:59:45',
                'updated_at' => '2020-11-06 15:06:50',
                'deleted_at' => '2020-11-06 15:06:50',
                'site_id' => null,
            ],
            9 => [
                'id' => 10,
                'name' => 'test3',
                'description' => '<p>fdfsdfsd</p>',
                'created_at' => '2020-11-06 15:07:07',
                'updated_at' => '2020-11-06 15:26:18',
                'deleted_at' => '2020-11-06 15:26:18',
                'site_id' => null,
            ],
            10 => [
                'id' => 11,
                'name' => 'test4',
                'description' => null,
                'created_at' => '2020-11-06 15:25:52',
                'updated_at' => '2020-11-06 15:26:18',
                'deleted_at' => '2020-11-06 15:26:18',
                'site_id' => null,
            ],
        ]);
    }
}
