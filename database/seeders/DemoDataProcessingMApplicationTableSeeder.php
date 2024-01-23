<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataProcessingMApplicationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_processing_m_application')->delete();
        
        \DB::table('data_processing_m_application')->insert(array (
            0 => 
            array (
                'data_processing_id' => 1,
                'm_application_id' => 15,
            ),
            1 => 
            array (
                'data_processing_id' => 1,
                'm_application_id' => 3,
            ),
            2 => 
            array (
                'data_processing_id' => 2,
                'm_application_id' => 1,
            ),
            3 => 
            array (
                'data_processing_id' => 4,
                'm_application_id' => 12,
            ),
        ));
        
        
    }
}