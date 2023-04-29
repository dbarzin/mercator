<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_modules')->delete();
        
        \DB::table('application_modules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Module 1',
                'description' => '<p>Description du module 1</p>',
                'created_at' => '2020-06-13 11:55:34',
                'updated_at' => '2020-06-13 11:55:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Module 2',
                'description' => '<p>Description du module 2</p>',
                'created_at' => '2020-06-13 11:55:45',
                'updated_at' => '2020-06-13 11:55:45',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Module 3',
                'description' => '<p>Description du module 3</p>',
                'created_at' => '2020-06-13 11:56:00',
                'updated_at' => '2020-06-13 11:56:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Module 4',
                'description' => '<p>Description du module 4</p>',
                'created_at' => '2020-06-13 11:56:10',
                'updated_at' => '2020-06-13 11:56:10',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Module 5',
                'description' => '<p>Description du module 5</p>',
                'created_at' => '2020-06-13 11:56:20',
                'updated_at' => '2020-06-13 11:56:20',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Module 6',
                'description' => '<p>Description du module 6</p>',
                'created_at' => '2020-06-13 11:56:32',
                'updated_at' => '2020-06-13 11:56:32',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}