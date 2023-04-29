<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActivityOperationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_operation')->delete();
        
        \DB::table('activity_operation')->insert(array (
            0 => 
            array (
                'activity_id' => 2,
                'operation_id' => 3,
            ),
            1 => 
            array (
                'activity_id' => 1,
                'operation_id' => 1,
            ),
            2 => 
            array (
                'activity_id' => 1,
                'operation_id' => 2,
            ),
            3 => 
            array (
                'activity_id' => 4,
                'operation_id' => 3,
            ),
            4 => 
            array (
                'activity_id' => 3,
                'operation_id' => 1,
            ),
            5 => 
            array (
                'activity_id' => 1,
                'operation_id' => 5,
            ),
            6 => 
            array (
                'activity_id' => 5,
                'operation_id' => 1,
            ),
            7 => 
            array (
                'activity_id' => 6,
                'operation_id' => 1,
            ),
            8 => 
            array (
                'activity_id' => 10,
                'operation_id' => 1,
            ),
        ));
        
        
    }
}