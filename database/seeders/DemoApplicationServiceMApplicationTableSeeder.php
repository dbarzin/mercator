<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationServiceMApplicationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_service_m_application')->delete();
        
        \DB::table('application_service_m_application')->insert(array (
            0 => 
            array (
                'm_application_id' => 2,
                'application_service_id' => 3,
            ),
            1 => 
            array (
                'm_application_id' => 2,
                'application_service_id' => 4,
            ),
            2 => 
            array (
                'm_application_id' => 1,
                'application_service_id' => 3,
            ),
            3 => 
            array (
                'm_application_id' => 15,
                'application_service_id' => 2,
            ),
            4 => 
            array (
                'm_application_id' => 15,
                'application_service_id' => 3,
            ),
            5 => 
            array (
                'm_application_id' => 1,
                'application_service_id' => 1,
            ),
            6 => 
            array (
                'm_application_id' => 4,
                'application_service_id' => 11,
            ),
            7 => 
            array (
                'm_application_id' => 4,
                'application_service_id' => 5,
            ),
            8 => 
            array (
                'm_application_id' => 2,
                'application_service_id' => 7,
            ),
            9 => 
            array (
                'm_application_id' => 4,
                'application_service_id' => 7,
            ),
            10 => 
            array (
                'm_application_id' => 1,
                'application_service_id' => 10,
            ),
            11 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 10,
            ),
            12 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 11,
            ),
            13 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 5,
            ),
            14 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 6,
            ),
            15 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 7,
            ),
            16 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 9,
            ),
            17 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 1,
            ),
            18 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 2,
            ),
            19 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 3,
            ),
            20 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 4,
            ),
            21 => 
            array (
                'm_application_id' => 16,
                'application_service_id' => 8,
            ),
        ));
        
        
    }
}