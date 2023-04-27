<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMApplicationProcessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('m_application_process')->delete();
        
        \DB::table('m_application_process')->insert(array (
            0 => 
            array (
                'm_application_id' => 2,
                'process_id' => 1,
            ),
            1 => 
            array (
                'm_application_id' => 2,
                'process_id' => 2,
            ),
            2 => 
            array (
                'm_application_id' => 3,
                'process_id' => 2,
            ),
            3 => 
            array (
                'm_application_id' => 1,
                'process_id' => 1,
            ),
            4 => 
            array (
                'm_application_id' => 14,
                'process_id' => 2,
            ),
            5 => 
            array (
                'm_application_id' => 4,
                'process_id' => 3,
            ),
            6 => 
            array (
                'm_application_id' => 12,
                'process_id' => 4,
            ),
            7 => 
            array (
                'm_application_id' => 16,
                'process_id' => 1,
            ),
            8 => 
            array (
                'm_application_id' => 16,
                'process_id' => 2,
            ),
            9 => 
            array (
                'm_application_id' => 16,
                'process_id' => 3,
            ),
            10 => 
            array (
                'm_application_id' => 16,
                'process_id' => 4,
            ),
            11 => 
            array (
                'm_application_id' => 16,
                'process_id' => 9,
            ),
        ));
        
        
    }
}