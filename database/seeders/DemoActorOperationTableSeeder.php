<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActorOperationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('actor_operation')->delete();
        
        \DB::table('actor_operation')->insert(array (
            0 => 
            array (
                'operation_id' => 2,
                'actor_id' => 1,
            ),
            1 => 
            array (
                'operation_id' => 1,
                'actor_id' => 1,
            ),
            2 => 
            array (
                'operation_id' => 1,
                'actor_id' => 4,
            ),
            3 => 
            array (
                'operation_id' => 2,
                'actor_id' => 5,
            ),
            4 => 
            array (
                'operation_id' => 3,
                'actor_id' => 6,
            ),
            5 => 
            array (
                'operation_id' => 5,
                'actor_id' => 4,
            ),
        ));
        
        
    }
}