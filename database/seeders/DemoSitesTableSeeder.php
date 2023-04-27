<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSitesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sites')->delete();
        
        \DB::table('sites')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Site A',
                'description' => '<p>Description du site A</p>',
                'created_at' => '2020-06-21 06:36:41',
                'updated_at' => '2020-06-21 06:36:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Site B',
                'description' => '<p>Description du site B</p>',
                'created_at' => '2020-06-21 06:36:53',
                'updated_at' => '2020-06-21 06:36:53',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Site C',
                'description' => '<p>Description du Site C</p>',
                'created_at' => '2020-06-21 06:37:05',
                'updated_at' => '2020-06-21 06:37:05',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Test1',
                'description' => '<p>site de test</p>',
                'created_at' => '2020-07-24 21:12:29',
                'updated_at' => '2020-07-24 21:12:56',
                'deleted_at' => '2020-07-24 21:12:56',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'testsite',
                'description' => '<p>description here</p>',
                'created_at' => '2021-04-12 17:31:40',
                'updated_at' => '2021-04-12 17:32:04',
                'deleted_at' => '2021-04-12 17:32:04',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Site Z',
                'description' => NULL,
                'created_at' => '2021-06-18 07:36:03',
                'updated_at' => '2021-10-19 18:51:22',
                'deleted_at' => '2021-10-19 18:51:22',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Site 0',
                'description' => NULL,
                'created_at' => '2021-06-18 07:36:12',
                'updated_at' => '2021-08-17 19:52:52',
                'deleted_at' => '2021-08-17 19:52:52',
            ),
        ));
        
        
    }
}