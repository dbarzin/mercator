<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActivityProcessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_process')->delete();
        
        \DB::table('activity_process')->insert(array (
            0 => 
            array (
                'process_id' => 1,
                'activity_id' => 1,
            ),
            1 => 
            array (
                'process_id' => 1,
                'activity_id' => 2,
            ),
            2 => 
            array (
                'process_id' => 2,
                'activity_id' => 3,
            ),
            3 => 
            array (
                'process_id' => 2,
                'activity_id' => 4,
            ),
            4 => 
            array (
                'process_id' => 3,
                'activity_id' => 2,
            ),
            5 => 
            array (
                'process_id' => 3,
                'activity_id' => 5,
            ),
            6 => 
            array (
                'process_id' => 4,
                'activity_id' => 5,
            ),
            7 => 
            array (
                'process_id' => 5,
                'activity_id' => 4,
            ),
            8 => 
            array (
                'process_id' => 6,
                'activity_id' => 4,
            ),
            9 => 
            array (
                'process_id' => 7,
                'activity_id' => 3,
            ),
            10 => 
            array (
                'process_id' => 8,
                'activity_id' => 4,
            ),
            11 => 
            array (
                'process_id' => 9,
                'activity_id' => 3,
            ),
            12 => 
            array (
                'process_id' => 1,
                'activity_id' => 10,
            ),
        ));
        
        
    }
}