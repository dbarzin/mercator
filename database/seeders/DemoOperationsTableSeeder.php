<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOperationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('operations')->delete();
        
        \DB::table('operations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Operation 1',
                'description' => '<p>Description de l\'opération</p>',
                'created_at' => '2020-06-13 02:02:42',
                'updated_at' => '2020-06-13 02:02:42',
                'deleted_at' => NULL,
                'process_id' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Operation 2',
                'description' => '<p>Description de l\'opération</p>',
                'created_at' => '2020-06-13 02:02:58',
                'updated_at' => '2020-06-13 02:02:58',
                'deleted_at' => NULL,
                'process_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Operation 3',
                'description' => '<p>Desciption de l\'opération</p>',
                'created_at' => '2020-06-13 02:03:11',
                'updated_at' => '2020-07-15 16:34:52',
                'deleted_at' => NULL,
                'process_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Operation 4',
                'description' => NULL,
                'created_at' => '2020-07-15 16:34:02',
                'updated_at' => '2020-07-15 16:34:02',
                'deleted_at' => NULL,
                'process_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Master operation',
                'description' => '<p>Opération maitre</p>',
                'created_at' => '2020-08-15 06:01:40',
                'updated_at' => '2020-08-15 06:01:40',
                'deleted_at' => NULL,
                'process_id' => NULL,
            ),
        ));
        
        
    }
}