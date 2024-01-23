<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataProcessingProcessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_processing_process')->delete();
        
        \DB::table('data_processing_process')->insert(array (
            0 => 
            array (
                'data_processing_id' => 1,
                'process_id' => 1,
            ),
            1 => 
            array (
                'data_processing_id' => 1,
                'process_id' => 2,
            ),
            2 => 
            array (
                'data_processing_id' => 2,
                'process_id' => 2,
            ),
            3 => 
            array (
                'data_processing_id' => 3,
                'process_id' => 3,
            ),
            4 => 
            array (
                'data_processing_id' => 4,
                'process_id' => 3,
            ),
        ));
        
        
    }
}