<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationModuleApplicationServiceTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_module_application_service')->delete();
        
        \DB::table('application_module_application_service')->insert(array (
            0 => 
            array (
                'application_service_id' => 4,
                'application_module_id' => 1,
            ),
            1 => 
            array (
                'application_service_id' => 4,
                'application_module_id' => 2,
            ),
            2 => 
            array (
                'application_service_id' => 3,
                'application_module_id' => 3,
            ),
            3 => 
            array (
                'application_service_id' => 2,
                'application_module_id' => 4,
            ),
            4 => 
            array (
                'application_service_id' => 1,
                'application_module_id' => 5,
            ),
            5 => 
            array (
                'application_service_id' => 1,
                'application_module_id' => 6,
            ),
            6 => 
            array (
                'application_service_id' => 5,
                'application_module_id' => 2,
            ),
            7 => 
            array (
                'application_service_id' => 5,
                'application_module_id' => 3,
            ),
            8 => 
            array (
                'application_service_id' => 6,
                'application_module_id' => 2,
            ),
            9 => 
            array (
                'application_service_id' => 6,
                'application_module_id' => 3,
            ),
            10 => 
            array (
                'application_service_id' => 7,
                'application_module_id' => 2,
            ),
            11 => 
            array (
                'application_service_id' => 7,
                'application_module_id' => 3,
            ),
            12 => 
            array (
                'application_service_id' => 8,
                'application_module_id' => 2,
            ),
            13 => 
            array (
                'application_service_id' => 8,
                'application_module_id' => 3,
            ),
            14 => 
            array (
                'application_service_id' => 9,
                'application_module_id' => 2,
            ),
            15 => 
            array (
                'application_service_id' => 9,
                'application_module_id' => 3,
            ),
            16 => 
            array (
                'application_service_id' => 10,
                'application_module_id' => 2,
            ),
            17 => 
            array (
                'application_service_id' => 10,
                'application_module_id' => 3,
            ),
            18 => 
            array (
                'application_service_id' => 11,
                'application_module_id' => 2,
            ),
            19 => 
            array (
                'application_service_id' => 11,
                'application_module_id' => 3,
            ),
        ));
        
        
    }
}