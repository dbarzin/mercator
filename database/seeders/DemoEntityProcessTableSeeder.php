<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEntityProcessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('entity_process')->delete();
        
        \DB::table('entity_process')->insert(array (
            0 => 
            array (
                'process_id' => 1,
                'entity_id' => 1,
            ),
            1 => 
            array (
                'process_id' => 2,
                'entity_id' => 1,
            ),
            2 => 
            array (
                'process_id' => 3,
                'entity_id' => 1,
            ),
            3 => 
            array (
                'process_id' => 1,
                'entity_id' => 13,
            ),
            4 => 
            array (
                'process_id' => 3,
                'entity_id' => 13,
            ),
            5 => 
            array (
                'process_id' => 4,
                'entity_id' => 1,
            ),
            6 => 
            array (
                'process_id' => 7,
                'entity_id' => 3,
            ),
            7 => 
            array (
                'process_id' => 9,
                'entity_id' => 4,
            ),
            8 => 
            array (
                'process_id' => 2,
                'entity_id' => 8,
            ),
            9 => 
            array (
                'process_id' => 4,
                'entity_id' => 6,
            ),
            10 => 
            array (
                'process_id' => 4,
                'entity_id' => 7,
            ),
            11 => 
            array (
                'process_id' => 9,
                'entity_id' => 5,
            ),
            12 => 
            array (
                'process_id' => 1,
                'entity_id' => 9,
            ),
            13 => 
            array (
                'process_id' => 2,
                'entity_id' => 9,
            ),
            14 => 
            array (
                'process_id' => 3,
                'entity_id' => 9,
            ),
            15 => 
            array (
                'process_id' => 4,
                'entity_id' => 9,
            ),
            16 => 
            array (
                'process_id' => 9,
                'entity_id' => 9,
            ),
            17 => 
            array (
                'process_id' => 1,
                'entity_id' => 12,
            ),
            18 => 
            array (
                'process_id' => 1,
                'entity_id' => 2,
            ),
            19 => 
            array (
                'process_id' => 4,
                'entity_id' => 18,
            ),
            20 => 
            array (
                'process_id' => 3,
                'entity_id' => 19,
            ),
        ));
        
        
    }
}