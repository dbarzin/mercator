<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoBaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bays')->delete();
        
        \DB::table('bays')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'BAIE 101',
                'description' => '<p>Description de la baie 101</p>',
                'created_at' => '2020-06-21 06:56:01',
                'updated_at' => '2021-10-19 18:45:21',
                'deleted_at' => NULL,
                'room_id' => 7,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'BAIE 102',
                'description' => '<p>Desciption baie 102</p>',
                'created_at' => '2020-06-21 06:56:20',
                'updated_at' => '2020-06-21 06:56:20',
                'deleted_at' => NULL,
                'room_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'BAIE 103',
                'description' => '<p>Descripton baid 103</p>',
                'created_at' => '2020-06-21 06:56:38',
                'updated_at' => '2020-06-21 06:56:38',
                'deleted_at' => NULL,
                'room_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'BAIE 201',
                'description' => '<p>Description baie 201</p>',
                'created_at' => '2020-06-21 06:56:55',
                'updated_at' => '2020-06-21 06:56:55',
                'deleted_at' => NULL,
                'room_id' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'BAIE 301',
                'description' => '<p>Baie 301</p>',
                'created_at' => '2020-07-15 20:03:07',
                'updated_at' => '2020-07-15 20:03:07',
                'deleted_at' => NULL,
                'room_id' => 3,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'BAIE 501',
                'description' => '<p>Baie 501</p>',
                'created_at' => '2020-07-15 20:10:23',
                'updated_at' => '2020-07-15 20:10:23',
                'deleted_at' => NULL,
                'room_id' => 5,
            ),
        ));
        
        
    }
}