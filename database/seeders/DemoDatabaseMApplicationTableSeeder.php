<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseMApplicationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('database_m_application')->delete();
        
        \DB::table('database_m_application')->insert(array (
            0 => 
            array (
                'm_application_id' => 2,
                'database_id' => 3,
            ),
            1 => 
            array (
                'm_application_id' => 3,
                'database_id' => 4,
            ),
            2 => 
            array (
                'm_application_id' => 3,
                'database_id' => 1,
            ),
            3 => 
            array (
                'm_application_id' => 4,
                'database_id' => 5,
            ),
            4 => 
            array (
                'm_application_id' => 4,
                'database_id' => 6,
            ),
            5 => 
            array (
                'm_application_id' => 15,
                'database_id' => 5,
            ),
            6 => 
            array (
                'm_application_id' => 15,
                'database_id' => 4,
            ),
            7 => 
            array (
                'm_application_id' => 16,
                'database_id' => 1,
            ),
        ));
        
        
    }
}