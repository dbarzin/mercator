<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActivitiesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('activities')->delete();

        \DB::table('activities')->insert([
            0 => [
                'name' => 'Activité 1',
                'description' => '<p>Description de l\'activité 1</p>',
                'created_at' => '2020-06-10 15:20:42',
                'updated_at' => '2020-06-10 15:20:42',
                'deleted_at' => null,
            ],
            1 => [
                'name' => 'Activité 2',
                'description' => '<p>Description de l\'activité de test</p>',
                'created_at' => '2020-06-10 17:44:26',
                'updated_at' => '2020-06-13 06:03:26',
                'deleted_at' => null,
            ],
            2 => [
                'name' => 'Activité 3',
                'description' => '<p>Description de l\'activité 3</p>',
                'created_at' => '2020-06-13 06:57:08',
                'updated_at' => '2020-06-13 06:57:08',
                'deleted_at' => null,
            ],
            3 => [
                'name' => 'Activité 4',
                'description' => '<p>Description de l\'activité 4</p>',
                'created_at' => '2020-06-13 06:57:24',
                'updated_at' => '2020-06-13 06:57:24',
                'deleted_at' => null,
            ],
            4 => [
                'name' => 'Activité 5',
                'description' => '<p>Description de l\'activité 5</p>',
                'created_at' => '2021-05-15 09:40:16',
                'updated_at' => '2021-05-15 09:40:16',
                'deleted_at' => null,
            ],
        ]);

    }
}
