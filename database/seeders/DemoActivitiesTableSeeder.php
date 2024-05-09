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

        \DB::table('activities')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Activité 1',
                'description' => '<p>Description de l\'activité 1</p>',
                'created_at' => '2020-06-10 15:20:42',
                'updated_at' => '2020-06-10 15:20:42',
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Activité 2',
                'description' => '<p>Description de l\'activité de test</p>',
                'created_at' => '2020-06-10 17:44:26',
                'updated_at' => '2020-06-13 06:03:26',
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Activité 3',
                'description' => '<p>Description de l\'activité 3</p>',
                'created_at' => '2020-06-13 06:57:08',
                'updated_at' => '2020-06-13 06:57:08',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Activité 4',
                'description' => '<p>Description de l\'acivité 4</p>',
                'created_at' => '2020-06-13 06:57:24',
                'updated_at' => '2020-06-13 06:57:24',
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Activité principale',
                'description' => '<p>Description de l\'activité principale</p>',
                'created_at' => '2020-08-15 06:19:53',
                'updated_at' => '2020-08-15 06:19:53',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'AAA',
                'description' => 'test a1',
                'created_at' => '2021-03-22 20:06:55',
                'updated_at' => '2021-03-22 20:07:00',
                'deleted_at' => '2021-03-22 20:07:00',
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'AAA',
                'description' => 'test AAA',
                'created_at' => '2021-03-22 20:13:43',
                'updated_at' => '2021-03-22 20:14:05',
                'deleted_at' => '2021-03-22 20:14:05',
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'AAA',
                'description' => 'test 2 aaa',
                'created_at' => '2021-03-22 20:14:16',
                'updated_at' => '2021-03-22 20:14:45',
                'deleted_at' => '2021-03-22 20:14:45',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'AAA1',
                'description' => 'test 3 AAA',
                'created_at' => '2021-03-22 20:14:40',
                'updated_at' => '2021-03-22 20:19:09',
                'deleted_at' => '2021-03-22 20:19:09',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'Activité 0',
                'description' => '<p>Description de l\'activité zéro</p>',
                'created_at' => NULL,
                'updated_at' => '2021-05-15 09:40:16',
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'test',
                'description' => 'dqqsd',
                'created_at' => '2021-08-02 22:03:46',
                'updated_at' => '2021-09-22 12:59:48',
                'deleted_at' => '2021-09-22 12:59:48',
            ),
        ));


    }
}
