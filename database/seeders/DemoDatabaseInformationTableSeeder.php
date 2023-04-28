<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseInformationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('database_information')->delete();
        
        \DB::table('database_information')->insert(array (
            0 => 
            array (
                'database_id' => 1,
                'information_id' => 1,
            ),
            1 => 
            array (
                'database_id' => 1,
                'information_id' => 2,
            ),
            2 => 
            array (
                'database_id' => 1,
                'information_id' => 3,
            ),
            3 => 
            array (
                'database_id' => 3,
                'information_id' => 2,
            ),
            4 => 
            array (
                'database_id' => 3,
                'information_id' => 3,
            ),
            5 => 
            array (
                'database_id' => 5,
                'information_id' => 1,
            ),
            6 => 
            array (
                'database_id' => 4,
                'information_id' => 2,
            ),
            7 => 
            array (
                'database_id' => 6,
                'information_id' => 2,
            ),
            8 => 
            array (
                'database_id' => 6,
                'information_id' => 3,
            ),
            9 => 
            array (
                'database_id' => 5,
                'information_id' => 5,
            ),
        ));
        
        
    }
}