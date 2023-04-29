<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOperationTaskTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('operation_task')->delete();
        
        \DB::table('operation_task')->insert(array (
            0 => 
            array (
                'operation_id' => 1,
                'task_id' => 1,
            ),
            1 => 
            array (
                'operation_id' => 1,
                'task_id' => 2,
            ),
            2 => 
            array (
                'operation_id' => 2,
                'task_id' => 1,
            ),
            3 => 
            array (
                'operation_id' => 3,
                'task_id' => 3,
            ),
            4 => 
            array (
                'operation_id' => 4,
                'task_id' => 2,
            ),
            5 => 
            array (
                'operation_id' => 5,
                'task_id' => 1,
            ),
            6 => 
            array (
                'operation_id' => 5,
                'task_id' => 2,
            ),
            7 => 
            array (
                'operation_id' => 5,
                'task_id' => 3,
            ),
        ));
        
        
    }
}