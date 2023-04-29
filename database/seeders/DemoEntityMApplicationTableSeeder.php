<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEntityMApplicationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('entity_m_application')->delete();
        
        \DB::table('entity_m_application')->insert(array (
            0 => 
            array (
                'm_application_id' => 2,
                'entity_id' => 1,
            ),
            1 => 
            array (
                'm_application_id' => 5,
                'entity_id' => 1,
            ),
            2 => 
            array (
                'm_application_id' => 7,
                'entity_id' => 2,
            ),
            3 => 
            array (
                'm_application_id' => 9,
                'entity_id' => 1,
            ),
            4 => 
            array (
                'm_application_id' => 10,
                'entity_id' => 1,
            ),
            5 => 
            array (
                'm_application_id' => 2,
                'entity_id' => 2,
            ),
            6 => 
            array (
                'm_application_id' => 11,
                'entity_id' => 1,
            ),
            7 => 
            array (
                'm_application_id' => 1,
                'entity_id' => 2,
            ),
            8 => 
            array (
                'm_application_id' => 1,
                'entity_id' => 8,
            ),
            9 => 
            array (
                'm_application_id' => 3,
                'entity_id' => 8,
            ),
            10 => 
            array (
                'm_application_id' => 4,
                'entity_id' => 8,
            ),
            11 => 
            array (
                'm_application_id' => 4,
                'entity_id' => 4,
            ),
            12 => 
            array (
                'm_application_id' => 16,
                'entity_id' => 2,
            ),
        ));
        
        
    }
}