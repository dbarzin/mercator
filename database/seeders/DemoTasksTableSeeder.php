<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoTasksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tasks')->delete();

        \DB::table('tasks')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Tâche 1',
                'description' => 'Descriptionde la tâche 1',
                'created_at' => '2020-06-13 02:04:07',
                'updated_at' => '2020-06-13 02:04:07',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Tache 2',
                'description' => 'Description de la tâche 2',
                'created_at' => '2020-06-13 02:04:21',
                'updated_at' => '2020-06-13 02:04:21',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Tâche 3',
                'description' => 'Description de la tâche 3',
                'created_at' => '2020-06-13 02:04:41',
                'updated_at' => '2020-06-13 02:04:41',
                'deleted_at' => NULL,
            ),
        ));


    }
}
